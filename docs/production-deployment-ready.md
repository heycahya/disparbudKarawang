# Panduan Teknis Kesiapan Rilis Produksi (Deployment Blueprint)

Dokumen ini berisi panduan teknis komprehensif untuk melakukan deployment aplikasi secara aman, optimal, dan stabil ke environment produksi.

## 1. Konfigurasi Environment Produksi

Langkah pertama yang esensial dalam deployment adalah memastikan keamanan file konfigurasi environment (`.env`) di server produksi.

- Ubah mode aplikasi menjadi produksi untuk mengaktifkan optimasi internal Laravel:
  ```env
  APP_ENV=production
  ```
- **SANGAT PENTING**: Matikan mode debug untuk mencegah kebocoran stack trace atau informasi sensitif jika terjadi error:
  ```env
  APP_DEBUG=false
  ```
- **Optimasi Cache Laravel**: Setelah konfigurasi `.env` sesuai, jalankan perintah artisan berikut untuk membuat cache agar framework tidak perlu memuat ulang file pada setiap request, sehingga mempercepat waktu respons aplikasi:
  ```bash
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  ```

## 2. Pipeline Build Frontend Assets

Aplikasi ini menggunakan teknologi Vue 3 dan Tailwind CSS dengan *bundler* Vite. Aset-aset ini harus di-build dan dikompresi agar siap diakses secara publik.

- Lakukan instalasi dependensi frontend (disarankan menggunakan `npm ci` untuk deployment yang konsisten):
  ```bash
  npm ci
  ```
- Lakukan kompilasi aset untuk *production*:
  ```bash
  npm run build
  ```
  Perintah ini akan menjalankan pipeline kompilasi Vite yang memastikan seluruh kode JavaScript dan CSS terkompresi, diminifikasi, dan di-bundle secara optimal (serta menghapus sisa debug/source map). Hasil kompilasi akan tersimpan di dalam direktori `public/build/`.

## 3. Strategi Migrasi Data & Storage

Proses migrasi dan manajemen aset di server produksi harus dieksekusi secara hati-hati agar tidak merusak data yang sudah ada maupun dependensi sistem.

- **Migrasi Database:** Eksekusi migrasi skema database terbaru dengan menambahkan argumen `--force`. Argumen ini diperlukan pada environment `production` untuk melewati prompt konfirmasi interaktif:
  ```bash
  php artisan migrate --force
  ```
- **Konfigurasi Tautan Symlink Storage:** Pastikan direktori *storage* untuk aset publik saling tertaut. Jalankan perintah berikut agar file lokal yang berada di `storage/app/public` terhubung ke `public/storage`:
  ```bash
  php artisan storage:link
  ```
  Ini penting agar integrasi sistem proxy penyimpanan eksternal (seperti konfigurasi pada layanan Cloudinary) dapat tetap terhubung dengan aman dan berfungsi untuk menangani aset statis atau upload pengguna tanpa ada tautan URL yang putus atau rentan keamanannya.
