# ✅ Checklist Deployment LMS - Jagoan Hosting

## 📦 Persiapan (Di Komputer Lokal)

- [ ] Build frontend: `npm run build`
- [ ] Install vendor: `composer install --no-dev`
- [ ] Siapkan file `lms_mysql_dump.sql`
- [ ] Zip project (tanpa node_modules, .git)

## 🌐 Setup di Jagoan Hosting

### cPanel
- [ ] Cek PHP version >= 8.1
- [ ] Aktifkan ekstensi PHP yang diperlukan
- [ ] Buat database MySQL
- [ ] Buat user database + assign ke database
- [ ] Import file `lms_mysql_dump.sql` via phpMyAdmin

### Upload & Konfigurasi
- [ ] Upload dan extract file zip ke `public_html`
- [ ] Buat/edit file `.env` dengan kredensial yang benar
- [ ] Set permission `storage` dan `bootstrap/cache` ke 755
- [ ] Buat `.htaccess` di root untuk redirect ke public

### Via SSH (Jika tersedia)
```bash
cd public_html
composer install --optimize-autoloader --no-dev
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🧪 Testing

- [ ] Akses website melalui browser
- [ ] Test login admin: `admin@test.com`
- [ ] Test halaman dashboard
- [ ] Test upload file
- [ ] Test fitur-fitur utama

## 🔧 Troubleshooting Quick

| Error | Solusi |
|-------|--------|
| 500 | Cek `.env` & permissions |
| Database error | Cek kredensial di `.env` |
| CSS tidak muncul | Pastikan `public/build` ada |
| 403 | Cek `.htaccess` & permissions |

---

📅 Terakhir diperbarui: 15 Desember 2025
