# Ngopibareng.id - Laravel Project

Project Laravel yang dikembangkan dengan fitur Role-Based Access Control (RBAC) dan sistem Deployment Otomatis (CI/CD) ke Shared Hosting.

## 🚀 Fitur Utama
- **Laravel 12**: Menggunakan versi terbaru Laravel.
- **RBAC (Role & Permission)**: Menggunakan `spatie/laravel-permission` untuk pengelolaan hak akses yang dinamis.
- **Webartisan Terminal**: Akses terminal artisan melalui browser (aman dengan password).
- **CI/CD Ready**: Otomatis deploy ke hosting (CPanel/InfinityFree) menggunakan GitHub Actions.
- **Shared Hosting Compatibility**: Dilengkapi dengan fix `defaultStringLength(191)` untuk kompatibilitas database MySQL versi lama.

## 🛠️ Persyaratan Sistem
- PHP >= 8.3
- Composer
- Node.js & NPM
- MySQL / MariaDB

## 📥 Cara Instalasi di Lokal

Ikuti langkah-langkah berikut untuk menjalankan project ini di komputer Anda:

1. **Clone Repositori**
   ```bash
   git clone https://github.com/Chandra2004/tes-lara.git
   cd tes-lara
   ```

2. **Instal Dependensi PHP**
   ```bash
   composer install
   ```

3. **Instal Dependensi JavaScript**
   ```bash
   npm install
   ```

4. **Setup Environment**
   Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```

5. **Generate App Key**
   ```bash
   php artisan key:generate
   ```

6. **Konfigurasi Database**
   Buka file `.env` dan sesuaikan pengaturan database Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_anda
   DB_USERNAME=root
   DB_PASSWORD=
   ```

7. **Jalankan Migrasi Database**
   ```bash
   php artisan migrate
   ```

8. **Build Asset Frontend**
   ```bash
   npm run dev
   ```

9. **Jalankan Server Lokal**
   ```bash
   php artisan serve
   ```
   Buka `http://localhost:8000` di browser Anda.

## 🌐 Cara Deploy ke Production

Project ini sudah dilengkapi dengan GitHub Actions. Untuk mengaktifkan deployment otomatis:

1. Masuk ke tab **Settings** > **Secrets and variables** > **Actions** di repositori GitHub Anda.
2. Tambahkan **Repository Secrets** berikut:
   - `FTP_SERVER`: Host FTP (misal: `ftp.domain.com`)
   - `FTP_USERNAME`: Username FTP Anda.
   - `FTP_PASSWORD`: Password FTP Anda.
   - `ENV_PROD_EXTRAS`: Isi lengkap file `.env` untuk production (termasuk DB production).

Setiap kali Anda melakukan `push` ke branch `main`, GitHub akan otomatis melakukan build dan upload ke hosting Anda.

## 📄 Lisensi
Project ini menggunakan lisensi MIT.
