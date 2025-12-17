# 👤 Panduan Akun Pengguna - LMS SMKS YASMU GRESIK

## 📋 Daftar Isi
1. [Jenis Akun](#jenis-akun)
2. [Kredensial Login Default](#kredensial-login-default)
3. [Cara Import Data & Pembuatan Akun Otomatis](#cara-import-data--pembuatan-akun-otomatis)
4. [Reset Password](#reset-password)
5. [FAQ](#faq)

---

## Jenis Akun

LMS SMKS YASMU memiliki 3 jenis akun pengguna:

| Role | Deskripsi | Akses |
|------|-----------|-------|
| **Admin** | Administrator sistem | Semua fitur, manajemen user |
| **Teacher (Guru)** | Tenaga pengajar | Materi, tugas, kuis, nilai |
| **Student (Siswa)** | Peserta didik | Akses materi, kerjakan tugas/kuis |

---

## Kredensial Login Default

### 🔐 Akun Admin
```
Email: admin@test.com
Password: password
```

> ⚠️ **PENTING:** Segera ubah password admin setelah pertama kali login!

---

### 👨‍🏫 Akun Guru

Akun guru dibuat **otomatis** saat import data guru via Excel.

| Field | Format | Contoh |
|-------|--------|--------|
| **Email** | Email dari Excel | `budisantoso@gmail.com` |
| **Password** | Kode Guru atau `123456` | `GR001` atau `123456` |

**Cara Login Guru:**
1. Buka halaman login: `/login`
2. Masukkan email (sesuai di Excel)
3. Masukkan password:
   - Jika ada **Kode Guru** di Excel → gunakan kode tersebut
   - Jika tidak ada → gunakan `123456`

---

### 👨‍🎓 Akun Siswa

Akun siswa dibuat **otomatis** saat import data nominatif siswa.

| Field | Format | Contoh |
|-------|--------|--------|
| **Email** | `{NIS}@siswa.smkyasmu.sch.id` | `12345@siswa.smkyasmu.sch.id` |
| **Password** | NIS (Nomor Induk Sekolah) | `12345` |

**Cara Login Siswa:**
1. Buka halaman login: `/login`
2. Masukkan email: `{NIS}@siswa.smkyasmu.sch.id`
   - Contoh: `12345@siswa.smkyasmu.sch.id`
3. Masukkan password: NIS
   - Contoh: `12345`

---

## Cara Import Data & Pembuatan Akun Otomatis

### Import Data Guru

1. Login sebagai **Admin**
2. Buka menu **Data Guru** → **Upload**
3. Download template Excel (jika perlu)
4. Upload file Excel dengan kolom:
   - Kolom A: Nama Lengkap
   - Kolom B: NIK
   - Kolom L: Email ← **WAJIB untuk membuat akun**
   - Kolom N: Kode Guru ← **Digunakan sebagai password**
5. Klik **Import**
6. Akun login akan dibuat otomatis untuk guru yang memiliki email

### Import Data Siswa

1. Login sebagai **Admin**
2. Buka menu **Data Siswa** → **Upload**
3. Upload file nominatif Excel
4. Klik **Import**
5. Akun login dibuat otomatis dengan format:
   - Email: `{NIS}@siswa.smkyasmu.sch.id`
   - Password: `{NIS}`

---

## Reset Password

### Via Admin Panel

Admin dapat mereset password pengguna:

1. Login sebagai **Admin**
2. Buka menu **Data Guru** atau **Data Siswa**
3. Cari pengguna yang ingin direset
4. Klik tombol **Reset Password**
5. Password akan direset ke default:
   - Guru: Kode Guru atau `123456`
   - Siswa: NIS

### Via Terminal (Untuk Developer)

```bash
# Reset password user tertentu
php artisan tinker
>>> $user = User::where('email', 'email@example.com')->first();
>>> $user->update(['password' => bcrypt('newpassword')]);
```

---

## FAQ

### Q: Saya lupa password, bagaimana cara reset?
**A:** Hubungi admin sekolah untuk mereset password Anda.

### Q: Kenapa saya tidak bisa login sebagai guru?
**A:** Pastikan:
1. Email Anda sudah terdaftar di sistem (ada di Excel saat import)
2. Password sesuai (Kode Guru atau `123456`)
3. Jika tetap tidak bisa, hubungi admin untuk reset password

### Q: Kenapa saya tidak bisa login sebagai siswa?
**A:** Pastikan:
1. Email menggunakan format: `{NIS}@siswa.smkyasmu.sch.id`
2. Password adalah NIS Anda
3. Data Anda sudah diimport oleh admin

### Q: Bagaimana cara mengubah password?
**A:** Saat ini, perubahan password hanya bisa dilakukan oleh admin. Fitur ubah password mandiri akan segera tersedia.

---

## 📞 Kontak Support

Jika mengalami masalah login:
- Hubungi Admin IT Sekolah
- Email: admin@smkyasmu.sch.id

---

📅 **Terakhir diperbarui:** 17 Desember 2025
