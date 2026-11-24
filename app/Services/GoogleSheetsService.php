<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class GoogleSheetsService
{
    private function getAccessToken()
    {
        $credentialsPath = storage_path('app/google/jurnal-siswa-smpn-9-fb22dcb69eb6.json');
        
        if (!file_exists($credentialsPath)) {
            throw new Exception("File kredensial Google JSON tidak ditemukan di: " . $credentialsPath);
        }

        $authConfig = json_decode(file_get_contents($credentialsPath), true);

        if (!isset($authConfig['client_email']) || !isset($authConfig['private_key']) || !isset($authConfig['token_uri'])) {
            throw new Exception("Format file JSON kredensial Google tidak lengkap.");
        }

        $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
        $now = time();
        $claim = json_encode([
            'iss' => $authConfig['client_email'],
            'scope' => 'https://www.googleapis.com/auth/spreadsheets',
            'aud' => $authConfig['token_uri'],
            'exp' => $now + 3600,
            'iat' => $now
        ]);

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlClaim = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($claim));

        $signatureInput = $base64UrlHeader . "." . $base64UrlClaim;
        
        // Memastikan format private key terbaca benar dengan mengganti escape character \n
        $privateKey = str_replace("\\n", "\n", $authConfig['private_key']);
        
        $signature = '';
        $signSuccess = openssl_sign($signatureInput, $signature, $privateKey, 'SHA256');

        if (!$signSuccess) {
            throw new Exception("Gagal melakukan enkripsi tanda tangan digital (openssl_sign) untuk Google Sheets API.");
        }

        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        $jwt = $signatureInput . "." . $base64UrlSignature;

        $response = Http::asForm()->post($authConfig['token_uri'], [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt
        ]);

        if (!$response->successful()) {
            throw new Exception("Gagal mendapatkan Access Token dari Google: " . $response->body());
        }

        return $response->json('access_token');
    }

    public function appendRow($sheetName, array $data)
    {
        try {
            $accessToken = $this->getAccessToken();
            $spreadsheetId = env('GOOGLE_SHEET_ID');
            
            if (empty($spreadsheetId)) {
                throw new Exception("GOOGLE_SHEET_ID belum diatur di file .env");
            }
            
            // Perbaikan: Menggunakan rawurlencode agar spasi pada nama sheet (seperti 'Taat Beribadah') terenkode menjadi %20 secara akurat untuk API Google Sheets
            $range = rawurlencode($sheetName) . '!A:Z';
            $url = "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheetId}/values/{$range}:append?valueInputOption=USER_ENTERED";

            $response = Http::withToken($accessToken)->post($url, [
                'values' => [$data]
            ]);

            if (!$response->successful()) {
                Log::error("Google Sheets API Error: " . $response->body());
                return false;
            }

            return true;
        } catch (Exception $e) {
            Log::error("GoogleSheetsService Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Memperbarui baris jika siswa dan tanggal sudah tercatat hari ini,
     * mengisi baris kosong jika ada yang dihapus sebelumnya,
     * atau menambahkan tepat di baris berikutnya tanpa melompat.
     */
    public function updateOrAppendRow($sheetName, array $data, $nameColIndex = 0, $dateColIndex = 1)
    {
        try {
            $accessToken = $this->getAccessToken();
            $spreadsheetId = env('GOOGLE_SHEET_ID');
            
            if (empty($spreadsheetId)) {
                throw new Exception("GOOGLE_SHEET_ID belum diatur di file .env");
            }
            
            $encodedSheet = rawurlencode($sheetName);
            // 1. Ambil kolom Nama dan Tanggal untuk cek duplikasi dan baris kosong
            $urlGet = "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheetId}/values/{$encodedSheet}!A:B";
            $responseGet = Http::withToken($accessToken)->get($urlGet);
            
            $targetRowIndex = null;
            $firstEmptyRowIndex = null;
            $totalRows = 0;

            if ($responseGet->successful()) {
                $rows = $responseGet->json('values') ?? [];
                $totalRows = count($rows);
                $targetName = trim(strtolower($data[$nameColIndex] ?? ''));
                $targetDate = trim($data[$dateColIndex] ?? '');
                
                foreach ($rows as $index => $row) {
                    if ($index === 0) continue; // Lewati baris header

                    $rowName = trim(strtolower($row[$nameColIndex] ?? ''));
                    $rowDate = trim($row[$dateColIndex] ?? '');

                    // Cocokkan data siswa di tanggal yang sama
                    if (!empty($targetName) && !empty($targetDate) && $rowName === $targetName && $rowDate === $targetDate) {
                        $targetRowIndex = $index + 1; // 1-based index untuk Google Sheets
                        break;
                    }

                    // Deteksi jika ada baris kosong / dihapus di antara data (misal baris 2 kosong)
                    if ($firstEmptyRowIndex === null && empty($rowName) && empty($rowDate)) {
                        $firstEmptyRowIndex = $index + 1;
                    }
                }
            }
            
            // Prioritas penentuan baris:
            // 1. Baris yang sudah ada dengan Nama & Tanggal yang sama (update in-place)
            // 2. Baris kosong pertama yang ditemukan (mengisi kembali baris yang sempat terhapus)
            // 3. Baris berikutnya tepat setelah baris terakhir
            $targetRow = $targetRowIndex ?? $firstEmptyRowIndex ?? ($totalRows > 0 ? $totalRows + 1 : 2);

            $colEnd = chr(65 + count($data) - 1); // Misal A sampai I
            $updateRange = "{$encodedSheet}!A{$targetRow}:{$colEnd}{$targetRow}";
            $urlPut = "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheetId}/values/{$updateRange}?valueInputOption=USER_ENTERED";
            
            $responsePut = Http::withToken($accessToken)->put($urlPut, [
                'values' => [$data]
            ]);
            
            if (!$responsePut->successful()) {
                Log::error("Google Sheets API Update Error: " . $responsePut->body());
                return $this->appendRow($sheetName, $data);
            }
            return true;
        } catch (Exception $e) {
            Log::error("GoogleSheetsService Exception in updateOrAppendRow: " . $e->getMessage());
            return $this->appendRow($sheetName, $data);
        }
    }
}