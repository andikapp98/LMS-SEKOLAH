# 🚀 Panduan Deployment - LMS Sekolah

## ✅ Checklist Sebelum Deploy

### File & Konfigurasi
- [x] Build production assets (`npm run build`)
- [x] Database backup tersedia
- [x] `.env.example` sudah lengkap
- [x] `.gitignore` sudah benar
- [x] `README.md` dokumentasi lengkap
- [x] API documentation lengkap

### Kode
- [x] Semua migration siap
- [x] Controller API lengkap
- [x] Model dengan relasi benar
- [x] Routes web & API terdefinisi

---

## 📋 Langkah-Langkah Deployment

### 1. Persiapan di Server

```bash
# Requirements
- PHP >= 8.2
- Composer >= 2.0
- Node.js >= 18
- PostgreSQL >= 14
- Nginx atau Apache
```

### 2. Clone Repository

```bash
git clone https://github.com/andikapp98/LMS-SEKOLAH.git
cd LMS-SEKOLAH
```

### 3. Install Dependencies

```bash
# PHP dependencies
composer install --optimize-autoloader --no-dev

# Node dependencies (untuk build)
npm install
npm run build
```

### 4. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```env
APP_NAME="LMS SMKS Yasmu"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://lms.smksyasmu.sch.id

DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=lms_sekolah
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# Mail (optional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
```

### 5. Setup Database

```bash
# Buat database
psql -U postgres -c "CREATE DATABASE lms_sekolah;"

# Jalankan migration
php artisan migrate --seed

# ATAU import dari backup
psql -U postgres -d lms_sekolah -f database/backups/lms_docker_backup.sql
```

### 6. Optimasi Laravel

```bash
# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Storage link
php artisan storage:link

# Set permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 7. Konfigurasi Nginx

```nginx
server {
    listen 80;
    server_name lms.smksyasmu.sch.id;
    root /var/www/lms-sekolah/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 8. SSL Certificate (HTTPS)

```bash
# Certbot untuk SSL gratis
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d lms.smksyasmu.sch.id
```

---

## 🔧 Troubleshooting

### Error 500
```bash
# Cek log
tail -f storage/logs/laravel.log

# Clear cache
php artisan optimize:clear
```

### Permission Error
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Database Connection Error
```bash
# Test koneksi
php artisan tinker
>>> DB::connection()->getPdo()
```

---

## 📱 Hosting yang Direkomendasikan

| Provider | Spesifikasi | Harga |
|----------|-------------|-------|
| **DigitalOcean** | 1 vCPU, 1GB RAM | $6/bulan |
| **Vultr** | 1 vCPU, 1GB RAM | $5/bulan |
| **AWS Lightsail** | 1 vCPU, 512MB RAM | $3.5/bulan |
| **Hostinger VPS** | 1 vCPU, 1GB RAM | $4/bulan |

Untuk shared hosting (cPanel):
- **Niagahoster** - Support PHP 8.2, PostgreSQL
- **Dewaweb** - Support Laravel

---

## 🔐 Keamanan Production

1. **Disable debug mode** - `APP_DEBUG=false`
2. **HTTPS** - Gunakan SSL certificate
3. **Secure headers** - Sudah di Nginx config
4. **Database** - Gunakan password kuat
5. **Backup** - Schedule backup database harian

---

## 📞 Support

Jika ada masalah deployment, cek:
1. Laravel logs: `storage/logs/laravel.log`
2. Nginx logs: `/var/log/nginx/error.log`
3. PHP logs: `/var/log/php8.2-fpm.log`
