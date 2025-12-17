# 🚀 Panduan Deployment LMS SMKS YASMU GRESIK
## Untuk Jagoan Hosting dengan MySQL Database

---

## 📋 Daftar Isi
1. [Persiapan](#1-persiapan)
2. [Konfigurasi Hosting](#2-konfigurasi-hosting)
3. [Upload File Aplikasi](#3-upload-file-aplikasi)
4. [Setup Database MySQL](#4-setup-database-mysql)
5. [Konfigurasi Environment](#5-konfigurasi-environment)
6. [Instalasi Dependencies](#6-instalasi-dependencies)
7. [Build Aplikasi](#7-build-aplikasi)
8. [Konfigurasi Web Server](#8-konfigurasi-web-server)
9. [Testing & Troubleshooting](#9-testing--troubleshooting)

---

## 1. Persiapan

### 1.1 Kebutuhan Sistem
| Komponen | Minimum | Rekomendasi |
|----------|---------|-------------|
| PHP | 8.1 | 8.2+ |
| MySQL | 5.7 | 8.0+ |
| Node.js | 18.x | 20.x |
| RAM | 512 MB | 1 GB+ |
| Storage | 1 GB | 5 GB+ |

### 1.2 File yang Diperlukan
- [ ] Source code aplikasi (zip)
- [ ] File `lms_mysql_dump.sql` (database dump)
- [ ] File `.env` yang sudah dikonfigurasi

### 1.3 Informasi yang Diperlukan dari Jagoan Hosting
- Domain/subdomain
- Username cPanel
- Password cPanel
- Nama database MySQL
- Username database
- Password database
- Host database (biasanya `localhost`)

---

## 2. Konfigurasi Hosting

### 2.1 Login ke cPanel
1. Buka URL cPanel: `https://namadomain.com:2083` atau via member area Jagoan Hosting
2. Masukkan username dan password cPanel

### 2.2 Cek Versi PHP
1. Buka **Select PHP Version** atau **MultiPHP Manager**
2. Pastikan versi PHP minimal **8.1**
3. Aktifkan ekstensi yang diperlukan:
   - `bcmath`
   - `ctype`
   - `curl`
   - `dom`
   - `fileinfo`
   - `gd`
   - `json`
   - `mbstring`
   - `openssl`
   - `pdo`
   - `pdo_mysql`
   - `tokenizer`
   - `xml`
   - `zip`

### 2.3 Setting PHP
Di **PHP Options** atau **php.ini**, atur:
```ini
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
memory_limit = 256M
```

---

## 3. Upload File Aplikasi

### 3.1 Persiapan File
1. Di komputer lokal, buat file zip dari project:
   - **JANGAN** include folder: `node_modules`, `vendor`, `.git`
   - Include semua file lainnya

2. Atau gunakan perintah (di folder project):
```bash
# Buat build production terlebih dahulu
npm run build

# Zip file (exclude yang tidak perlu)
zip -r lms-sekolah.zip . -x "node_modules/*" -x "vendor/*" -x ".git/*" -x "storage/logs/*"
```

### 3.2 Upload via File Manager
1. Buka **File Manager** di cPanel
2. Navigasi ke folder `public_html` (atau subfolder jika ingin di subdirectory)
3. Klik **Upload** → pilih file `lms-sekolah.zip`
4. Setelah upload selesai, **Extract** file zip tersebut
5. Hapus file zip setelah extract

### 3.3 Struktur Folder
Setelah extract, struktur folder harus seperti ini:
```
public_html/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
│   ├── index.php
│   ├── build/
│   └── ...
├── resources/
├── routes/
├── storage/
├── vendor/           # akan dibuat setelah composer install
├── .env
├── artisan
├── composer.json
└── ...
```

---

## 4. Setup Database MySQL

### 4.1 Membuat Database
1. Buka **MySQL Databases** di cPanel
2. Buat database baru:
   - Nama Database: `lms_sekolah` (atau sesuai keinginan)
   - Klik **Create Database**

### 4.2 Membuat User Database
1. Di halaman yang sama, scroll ke **MySQL Users**
2. Buat user baru:
   - Username: `lms_user` (atau sesuai keinginan)
   - Password: **(buat password yang kuat)**
   - Klik **Create User**

### 4.3 Assign User ke Database
1. Di bagian **Add User To Database**:
   - Pilih user yang baru dibuat
   - Pilih database yang baru dibuat
   - Klik **Add**
2. Di halaman privileges, centang **ALL PRIVILEGES**
3. Klik **Make Changes**

### 4.4 Import Database
#### Metode 1: Via phpMyAdmin (Rekomendasi untuk file kecil)
1. Buka **phpMyAdmin** di cPanel
2. Pilih database yang sudah dibuat
3. Klik tab **Import**
4. Klik **Choose File** → pilih file `lms_mysql_dump.sql`
5. Klik **Go**

#### Metode 2: Via Terminal/SSH (Rekomendasi untuk file besar)
```bash
# Login ke SSH terlebih dahulu
mysql -u USERNAME_DB -p NAMA_DATABASE < lms_mysql_dump.sql
```

#### Metode 3: Via Remote Database (Jika import gagal)
1. Di member area Jagoan Hosting, aktifkan **Remote MySQL**
2. Tambahkan IP komputer Anda
3. Import menggunakan MySQL Workbench atau HeidiSQL dari komputer lokal

---

## 5. Konfigurasi Environment

### 5.1 Buat/Edit File .env
Di folder aplikasi, buat atau edit file `.env`:

```env
APP_NAME="LMS SMKS YASMU GRESIK"
APP_ENV=production
APP_KEY=base64:XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
APP_DEBUG=false
APP_TIMEZONE=Asia/Jakarta
APP_URL=https://namadomain.com

APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID

LOG_CHANNEL=daily
LOG_LEVEL=error

# Database MySQL Jagoan Hosting
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=cpaneluser_lms_sekolah
DB_USERNAME=cpaneluser_lms_user
DB_PASSWORD=password_database_anda

# Session & Cache
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=true

CACHE_STORE=file
QUEUE_CONNECTION=sync

# Mail (opsional)
MAIL_MAILER=smtp
MAIL_HOST=mail.namadomain.com
MAIL_PORT=465
MAIL_USERNAME=noreply@namadomain.com
MAIL_PASSWORD=password_email
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=noreply@namadomain.com
MAIL_FROM_NAME="${APP_NAME}"

# Filesystem
FILESYSTEM_DISK=local
```

### 5.2 Generate App Key (Jika belum ada)
```bash
php artisan key:generate
```

### 5.3 Catatan Penting Database
- Di Jagoan Hosting, nama database biasanya: `cpaneluser_namadb`
- Username database biasanya: `cpaneluser_username`
- Ganti `cpaneluser` dengan username cPanel Anda

---

## 6. Instalasi Dependencies

### 6.1 Via SSH (Rekomendasi)
Jika hosting mendukung SSH:
```bash
# Masuk ke folder aplikasi
cd public_html

# Install composer dependencies
composer install --optimize-autoloader --no-dev

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimize untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Jalankan migration (jika database kosong)
# SKIP jika sudah import SQL
# php artisan migrate --force
```

### 6.2 Tanpa SSH
Jika tidak ada akses SSH:
1. Install `composer` dan `vendor` di komputer lokal
2. Zip folder `vendor`
3. Upload dan extract di hosting

---

## 7. Build Aplikasi

### 7.1 Build Frontend (Sebelum Upload)
Di komputer lokal, jalankan:
```bash
npm install
npm run build
```

Folder `public/build` akan dibuat. Pastikan folder ini ter-upload.

### 7.2 Verifikasi Build
Pastikan file-file berikut ada di hosting:
```
public/
├── build/
│   ├── assets/
│   │   ├── app-XXXXXX.js
│   │   └── app-XXXXXX.css
│   └── manifest.json
├── index.php
└── .htaccess
```

---

## 8. Konfigurasi Web Server

### 8.1 Setup Document Root
1. Buka **Domains** atau **Addon Domains** di cPanel
2. Atur **Document Root** ke folder `public_html/public`

   Jika tidak bisa mengubah document root, gunakan metode `.htaccess` di bawah.

### 8.2 File .htaccess di Root (public_html)
Buat file `.htaccess` di `public_html/` (bukan di public):
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### 8.3 File .htaccess di public
Pastikan file `public/.htaccess` berisi:
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### 8.4 Permission Folder
Set permission yang benar:
```bash
# Via SSH
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

Atau via File Manager:
1. Klik kanan folder `storage` → **Change Permissions**
2. Set ke **755** (read/write/execute untuk owner)
3. Lakukan hal yang sama untuk `bootstrap/cache`

---

## 9. Testing & Troubleshooting

### 9.1 Test Aplikasi
1. Buka browser dan akses domain Anda
2. Coba login dengan akun admin:
   - Email: `admin@test.com`
   - Password: `password` (atau sesuai yang di-seed)

### 9.2 Troubleshooting Umum

#### Error 500 - Internal Server Error
**Penyebab & Solusi:**
1. Cek file `.env` sudah ada dan benar
2. Jalankan: `php artisan config:clear`
3. Cek permission folder `storage` dan `bootstrap/cache`
4. Lihat error log di `storage/logs/laravel.log`

#### Error Database Connection
**Cek:**
1. Nama database, username, password di `.env`
2. Pastikan format nama: `cpaneluser_namadb`
3. Cek koneksi database di phpMyAdmin

#### Halaman Blank / CSS Tidak Muncul
**Penyebab & Solusi:**
1. Pastikan folder `public/build` ter-upload
2. Cek `APP_URL` di `.env` sudah benar
3. Jalankan `npm run build` ulang dan upload kembali

#### Error 403 Forbidden
**Solusi:**
1. Cek file `public/index.php` ada
2. Cek permission folder (755)
3. Pastikan `.htaccess` sudah benar

#### Session/Login Tidak Berfungsi
**Solusi:**
1. Pastikan `SESSION_DRIVER=file` di `.env`
2. Jalankan: `php artisan config:cache`
3. Hapus cookies browser

### 9.3 Monitoring & Maintenance
1. **Log Rotation**: Cek folder `storage/logs` secara berkala
2. **Backup**: Lakukan backup database rutin via cPanel
3. **Cache**: Bersihkan cache jika ada perubahan:
   ```bash
   php artisan cache:clear
   php artisan config:cache
   ```

---

## 📞 Kontak & Dukungan

### Jagoan Hosting Support
- Website: https://www.jagoanhosting.com
- Live Chat: Tersedia di website
- Email: support@jagoanhosting.com

### Developer
- SMKS YASMU GRESIK
- © 2025 All Rights Reserved

---

## 📝 Changelog

| Tanggal | Perubahan |
|---------|-----------|
| 2025-12-15 | Dokumentasi awal untuk deployment MySQL |

---

> **⚠️ PENTING**: Simpan semua kredensial (password database, dll.) dengan aman. Jangan share file `.env` ke repository public!
