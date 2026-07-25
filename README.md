# 🌿 VibeKarawang — Portal Resmi DISPARBUD Kabupaten Karawang

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 11" />
  <img src="https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=for-the-badge&logo=vuedotjs&logoColor=white" alt="Vue 3" />
  <img src="https://img.shields.io/badge/Inertia.js-Monolith_SPA-9553E9?style=for-the-badge&logo=inertia&logoColor=white" alt="Inertia.js" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-38BDF8?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind CSS" />
  <img src="https://img.shields.io/badge/Leaflet-1.x-[#199900]?style=for-the-badge&logo=leaflet&logoColor=white" alt="Leaflet JS" />
  <img src="https://img.shields.io/badge/Pest_PHP-Testing-34D399?style=for-the-badge&logo=php&logoColor=white" alt="Pest PHP" />
  <img src="https://img.shields.io/badge/Docker-Laravel_Sail-2496ED?style=for-the-badge&logo=docker&logoColor=white" alt="Laravel Sail" />
</p>

---

## 📌 Ringkasan Proyek

**VibeKarawang** adalah platform web portal yang dikembangkan sebagai **proyek perkuliahan (akademis)** untuk mensimulasikan sistem portal resmi Dinas Pariwisata dan Kebudayaan (DISPARBUD) Kabupaten Karawang. Platform ini dirancang untuk mempromosikan potensi pariwisata, kekayaan seni & kebudayaan, pelaku ekonomi kreatif, tempat kuliner khas, serta akomodasi di Karawang secara interaktif. 

Selain sebagai katalog informasi publik, VibeKarawang menyediakan modul **Service Rakyat** untuk memfasilitasi aspirasi publik, pengaduan masyarakat, serta pengajuan siaran acara budaya secara transparan dan terintegrasi.

---

## ✨ Fitur Utama

### 🌐 1. Portal Publik (Katalog & Informasi Interaktif)
- **Destinasi Wisata**: Informasi lengkap lokasi wisata alam, bersejarah, tirta, dan buatan di Karawang.
- **Seni & Kebudayaan**: Dokumentasi tari tradisional (Jaipong, Seni Ajeng), cagar budaya, dan warisan leluhur.
- **Ekonomi Kreatif (Ekraf)**: Sentra UMKM, kerajinan batik Karawang, dan produk lokal kreatif.
- **Kuliner Lokal**: Rekomendasi tempat makan khas, resto legendaris, dan jajanan tradisional.
- **Akomodasi**: Direktori hotel, villa, dan homestay bagi para wisatawan.
- **🗺️ Peta Interaktif Leaflet.js**: Visualisasi spasial penanda lokasi geografis berbasis koordinat Latitude & Longitude.
- **🖼️ Galeri Foto & Dokumentasi**: Koleksi dokumentasi visual kegiatan dan estetika budaya Karawang.
- **📰 Berita & Pengumuman**: Berita resmi terupdate dari Disparbud Karawang.

### 🤝 2. Service Rakyat (Layanan Publik Terpadu)
- **📢 Pengaduan Masyarakat (Complaints)**: Pelaporan kendala fasilitas publik pariwisata atau budaya.
- **🏞️ Usulan Wisata Baru**: Pengajuan tempat wisata baru oleh warga untuk ditinjau oleh dinas.
- **📅 Pengajuan Siaran Acara (Event Broadcast)**: Permohonan pengumuman kegiatan budaya/event komunitas.

### 🛡️ 3. Dashboard Pengguna & Admin CMS
- **👤 Panel Member (Role Public)**: Dashboard masyarakat untuk memantau status review pengaduan & usulan.
- **⚙️ Panel Admin CMS**:
  - Manajemen Katalog Konten (Create, Edit, Delete pada 7 modul utama).
  - Verifikasi Layanan (Moderasi status pengaduan/usulan serta fitur *Clone to Public Catalog*).
  - Manajemen Akun Pengguna & Reset Password.

---

## 🛠️ Tech Stack & Arsitektur

| Layer | Teknologi / Library | Deskripsi |
| :--- | :--- | :--- |
| **Backend Framework** | **Laravel 11** | Framework PHP modern dengan struktur modular & ORM Eloquent |
| **Frontend Framework** | **Vue 3** | Composition API (`<script setup>`) untuk UI komponen reaktif |
| **Glue Layer** | **Inertia.js** | Monolithic SPA bridge (bebas kerumitan API routing terpisah) |
| **Styling & UI** | **Tailwind CSS** | Design system modern, responsive layout, dark mode, & glassmorphism |
| **Geospatial Map** | **Leaflet.js** | Interaktif map renderer dengan TileLayer & Custom Marker Pin |
| **Cloud Storage** | **Cloudinary Service** | Penyimpanan & transformasi media gambar via `CloudinaryService` |
| **Testing** | **Pest PHP** | Automated unit & feature test suite |
| **Containerization** | **Laravel Sail** | Environment Docker bawaan (PHP, MySQL 8, Redis) |

---

## 🚀 Panduan Setup & Instalasi Lokal

Anda dapat menjalankan aplikasi VibeKarawang di lingkungan lokal menggunakan **Opsi A (Docker Sail)** atau **Opsi B (Tanpa Docker / Native PHP & MySQL)**.

---

### 🐳 Opsi A: Menggunakan Laravel Sail (Docker Compose) — *Rekomendasi*

Jika Anda telah menginstal **Docker Desktop**, cara ini adalah yang paling cepat dan praktis:

1. **Clone Repository & Environment Setup**
   ```bash
   git clone https://github.com/heycahya/disparbudKarawang.git
   cd disparbudKarawang
   cp .env.example .env
   ```

2. **Jalankan Container Docker**
   ```bash
   ./vendor/bin/sail up -d
   ```

3. **Migrasi & Seeding Database**
   ```bash
   ./vendor/bin/sail artisan migrate:fresh --seed
   ```

4. **Build Assets Frontend**
   ```bash
   # Mode Development
   ./vendor/bin/sail npm run dev

   # Mode Production Build
   rm -rf public/build && ./vendor/bin/sail npm run build
   ```

5. **Jalankan Testing (Pest)**
   ```bash
   ./vendor/bin/sail pest
   ```

---

### 💻 Opsi B: Tanpa Docker (Native PHP, Composer, Node.js & MySQL)

Jika Anda tidak menggunakan Docker, Anda dapat menjalankannya langsung menggunakan stack PHP & MySQL lokal (XAMPP / Laragon / PHP CLI):

#### Prasyarat Sistem:
- **PHP** `>= 8.2` (dengan ekstensi `pdo_mysql`, `mbstring`, `gd`, `curl`, `bcmath`, `xml`, `zip`)
- **Composer** `>= 2.x`
- **Node.js** `>= 18.x` & **NPM**
- **MySQL** / **MariaDB** Database Server

#### Langkah Instalasi:

1. **Clone Repository & Copy File Environment**
   ```bash
   git clone https://github.com/heycahya/disparbudKarawang.git
   cd disparbudKarawang
   cp .env.example .env
   ```

2. **Install Depedensi PHP via Composer**
   ```bash
   composer install
   ```

3. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

4. **Konfigurasi Database pada `.env`**
   Buat database baru di MySQL lokal Anda (contoh nama: `disparbud_karawang`), lalu buka file `.env` dan sesuaikan koneksinya:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=disparbud_karawang
   DB_USERNAME=root
   DB_PASSWORD=
   ```

   *(Opsional) Konfigurasi Cloudinary API jika ingin menguji upload gambar:*
   ```env
   CLOUDINARY_CLOUD_NAME=mabhpcw6
   CLOUDINARY_API_KEY=your_api_key
   CLOUDINARY_API_SECRET=your_api_secret
   ```

5. **Jalankan Migrasi & Seeder Database**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Install Depedensi Frontend & Compile Asset**
   ```bash
   # Install NPM packages
   npm install

   # Jalankan Server Frontend Vite (Development)
   npm run dev

   # ATAU Compile Asset untuk Production
   npm run build
   ```

7. **Jalankan Local Development Server**
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui browser di **`http://127.0.0.1:8000`**.

8. **Jalankan Automated Test (Pest)**
   ```bash
   php artisan test
   # ATAU
   ./vendor/bin/pest
   ```

---

## 🔑 Akun Default Seeder (Testing Credentials)

Setelah menjalankan `migrate:fresh --seed`, Anda dapat menguji akses sistem menggunakan akun default berikut:

| Role | Email | Password | Hak Akses |
| :--- | :--- | :--- | :--- |
| **Admin Disparbud** | `admin@disparbud.test` | `password` | Akses penuh Panel Admin CMS & Verifikasi Layanan |
| **Masyarakat 1** | `public1@example.com` | `password` | Pengajuan Service Rakyat & Member Dashboard |
| **Masyarakat 2** | `public2@example.com` | `password` | Pengajuan Service Rakyat & Member Dashboard |

---

## 🎓 Informasi Proyek Akademik & Dummy Data
Proyek aplikasi web **VibeKarawang** ini dikembangkan untuk memenuhi tugas perkuliahan / akademis. 

> [!NOTE]
> **Penafian Data Dummy**: Seluruh aset gambar, dokumentasi foto, teks deskripsi, serta record database yang ada dalam aplikasi ini menggunakan **data dummy (simulasi)** yang bersumber dari sampel publik/Unsplash semata untuk keperluan demonstrasi fungsionalitas aplikasi dan portofolio pengembangan perangkat lunak.
