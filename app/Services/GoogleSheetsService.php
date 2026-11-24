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
     * atau menambahkan baris baru jika belum ada.
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
            // 1. Ambil kolom Nama dan Tanggal untuk cek duplikasi
            $urlGet = "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheetId}/values/{$encodedSheet}!A:B";
            $responseGet = Http::withToken($accessToken)->get($urlGet);
            
            $targetRowIndex = null;
            if ($responseGet->successful()) {
                $rows = $responseGet->json('values') ?? [];
                $targetName = trim(strtolower($data[$nameColIndex] ?? ''));
                $targetDate = trim($data[$dateColIndex] ?? '');
                
                foreach ($rows as $index => $row) {
                    $rowName = trim(strtolower($row[$nameColIndex] ?? ''));
                    $rowDate = trim($row[$dateColIndex] ?? '');
                    if ($rowName === $targetName && $rowDate === $targetDate) {
                        $targetRowIndex = $index + 1; // 1-based index untuk Google Sheets
                        break;
                    }
                }
            }
            
            // 2. Jika baris siswa di tanggal ini sudah ada, perbarui baris tersebut
            if ($targetRowIndex !== null) {
                $colEnd = chr(65 + count($data) - 1); // Misal A sampai I
                $updateRange = "{$encodedSheet}!A{$targetRowIndex}:{$colEnd}{$targetRowIndex}";
                $urlPut = "https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheetId}/values/{$updateRange}?valueInputOption=USER_ENTERED";
                
                $responsePut = Http::withToken($accessToken)->put($urlPut, [
                    'values' => [$data]
                ]);
                
                if (!$responsePut->successful()) {
                    Log::error("Google Sheets API Update Error: " . $responsePut->body());
                    return false;
                }
                return true;
            }
            
            // 3. Jika belum ada, gunakan appendRow biasa
            return $this->appendRow($sheetName, $data);
        } catch (Exception $e) {
            Log::error("GoogleSheetsService Exception in updateOrAppendRow: " . $e->getMessage());
            return $this->appendRow($sheetName, $data);
        }
    }
}