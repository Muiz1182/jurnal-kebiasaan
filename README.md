# Jurnal Kebiasaan Siswa - SMPN 9 Probolinggo

Sistem Portal Akademik dan Pemantauan Jurnal Karakter 7 Kebiasaan Anak Indonesia Hebat di SMPN 9 Probolinggo. Aplikasi ini dibangun berbasis Laravel 12 dengan antarmuka responsif dan terintegrasi langsung dengan Google Spreadsheet untuk kemudahan rekapitulasi guru serta wali kelas secara *real-time*.

---

## Fitur Utama

### 1. Portal 7 Kebiasaan Siswa
- **Bangun Pagi**: Pencatatan rutinitas bangun pagi sebelum fajar untuk membentuk kedisiplinan sejak dini.
- **Taat Beribadah**: Pemantauan ibadah sholat 5 waktu dan baca Al-Qur'an. Dilengkapi proteksi penguncian waktu otomatis (tombol centang hanya terbuka 5 menit sebelum waktu sholat dan tertutup 1 jam setelah waktu sholat usai).
- **Rajin Olahraga**: Pencatatan jenis aktivitas fisik, intensitas olahraga, durasi, dan hidrasi.
- **Makan Sehat & Bergizi**: Pencatatan asupan gizi harian (sarapan, makan siang, makan malam, cemilan sehat) dengan panduan porsi seimbang sesuai pedoman Isi Piringku Kemenkes RI (1 mangkok sayur matang, 1 buah sedang/potong, dan target hidrasi 8 gelas air putih). Dilengkapi analisis otomatis kelayakan gizi harian (*Sangat Sehat*, *Cukup Sehat*, atau *Perlu Peningkatan*).
- **Gemar Belajar & Literasi**: Pencatatan materi baru, literasi buku (judul dan halaman terakhir), durasi belajar harian (target minimal 30 menit), latihan keterampilan/hobi positif, serta catatan refleksi belajar. Dilengkapi visualisasi Pohon Pengetahuan yang bertumbuh lebat seiring bertambahnya sesi belajar.
- **Aktif Bermasyarakat**: Pencatatan kegiatan sosial, gotong royong, dan kepedulian lingkungan.
- **Tidur Cepat**: Pemantauan jam istirahat malam dan kualitas tidur untuk menjaga kesehatan fisik serta konsentrasi belajar.

### 2. Panel Guru & Administrator
- Manajemen akun guru dan siswa.
- Pemantauan statistik keaktifan dan pengisian jurnal seluruh kelas.
- Rekapitulasi nilai dan kehadiran siswa.

### 3. Integrasi Google Spreadsheet
- Setiap jurnal kebiasaan yang disimpan siswa otomatis tersinkronisasi ke Google Spreadsheet sekolah.
- Menggunakan mekanisme *in-place update* sehingga pembaruan jurnal pada hari yang sama tidak menimbulkan duplikasi baris.

---

## Teknologi yang Digunakan

- **Backend**: Laravel 12 (PHP 8.2+)
- **Database**: MySQL / MariaDB
- **Frontend**: Blade Templating, Tailwind CSS, Alpine.js
- **API**: Google Sheets API v4 (Google Service Account)
- **Autentikasi**: Laravel Session Auth dengan perlindungan CSRF dan penanganan sesi kedaluwarsa

---

## Panduan Instalasi Lokal

### 1. Kloning Repositori
```bash
git clone https://github.com/Muiz1182/jurnal-kebiasaan.git
cd jurnal-kebiasaan
```

### 2. Instal Dependensi
```bash
composer install
npm install
```

### 3. Konfigurasi Lingkungan (.env)
Salin berkas konfigurasi contoh dan buat berkas `.env`:
```bash
cp .env.example .env
```

Atur konfigurasi database pada `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=root
DB_PASSWORD=
```

Generate App Key:
```bash
php artisan key:generate
```

### 4. Konfigurasi Google Sheets API (Opsional untuk Sinkronisasi)
1. Buat Service Account pada Google Cloud Console dan aktifkan **Google Sheets API**.
2. Unduh berkas JSON kredensial dan simpan pada direktori `storage/app/google/credentials.json`.
3. Buat Google Spreadsheet baru dengan tab:
   - `Bangun Pagi`
   - `Taat Beribadah`
   - `Rajin Olahraga`
   - `Makan Sehat`
   - `Gemar Belajar`
   - `Aktif Masyarakat`
   - `Tidur Cepat`
4. Bagikan akses Google Spreadsheet tersebut ke email Service Account (sebagai *Editor*).
5. Masukkan Spreadsheet ID ke dalam berkas `.env`:
   ```env
   GOOGLE_SHEET_ID=masukkan_id_spreadsheet_anda
   ```

### 5. Jalankan Migrasi Database
```bash
php artisan migrate --seed
```

### 6. Jalankan Server Aplikasi
```bash
php artisan serve
```
Akses aplikasi melalui peramban di: `http://127.0.0.1:8000`

---

## Keamanan Data

Demi menjaga kerahasiaan dan keamanan sistem:
- Berkas kredensial Google Service Account (`*.json`) dan berkas konfigurasi lingkungan (`.env`) **tidak disertakan** di dalam repositori publik ini dan telah dikecualikan melalui `.gitignore`.
- Seluruh formulir aplikasi dilengkapi enkripsi CSRF token untuk mencegah serangan eksploitasi web.

---

## Lisensi

Proyek ini dikembangkan untuk kebutuhan internal SMPN 9 Probolinggo di bawah lisensi [MIT](LICENSE).
