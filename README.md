# 🎓 SMKS Yasmu Gresik - Learning Management System

<p align="center">
  <img src="public/images/logo.png" alt="SMKS Yasmu Logo" width="120">
</p>

<p align="center">
  <b>Sistem Manajemen Pembelajaran Modern untuk SMKS Yasmu Gresik</b>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-red?style=flat-square&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/Vue.js-3.x-green?style=flat-square&logo=vue.js" alt="Vue.js">
  <img src="https://img.shields.io/badge/Inertia.js-1.x-purple?style=flat-square" alt="Inertia.js">
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-blue?style=flat-square&logo=tailwindcss" alt="TailwindCSS">
  <img src="https://img.shields.io/badge/PostgreSQL-Latest-blue?style=flat-square&logo=postgresql" alt="PostgreSQL">
</p>

---

## 📋 Daftar Isi

- [Fitur](#-fitur)
- [Teknologi](#-teknologi)
- [Persyaratan Sistem](#-persyaratan-sistem)
- [Instalasi](#-instalasi)
- [Konfigurasi](#-konfigurasi)
- [Penggunaan](#-penggunaan)
- [Struktur Proyek](#-struktur-proyek)
- [API Documentation](#-api-documentation)
- [Screenshots](#-screenshots)
- [Kontribusi](#-kontribusi)
- [Lisensi](#-lisensi)

---

## ✨ Fitur

### 👨‍💼 Admin
- ✅ Dashboard statistik lengkap
- ✅ Manajemen data siswa (CRUD + Import Excel)
- ✅ Manajemen data guru (CRUD + Import Excel)
- ✅ Manajemen mata pelajaran
- ✅ Manajemen tugas untuk semua kelas
- ✅ Manajemen kuis interaktif
- ✅ Manajemen materi pembelajaran
- ✅ Log aktivitas sistem

### 👨‍🏫 Guru
- ✅ Dashboard personal dengan statistik mata pelajaran
- ✅ Pembuatan dan manajemen tugas
- ✅ Pembuatan kuis interaktif (PG, Benar/Salah, Essay)
- ✅ Upload materi pembelajaran
- ✅ Melihat hasil kuis siswa
- ✅ Penargetan tugas/kuis ke kelas tertentu

### 👨‍🎓 Siswa
- ✅ Dashboard informatif dengan tugas & kuis tersedia
- ✅ Akses tugas sesuai kelas
- ✅ Mengerjakan kuis dengan timer
- ✅ Melihat nilai/hasil kuis
- ✅ Download materi pembelajaran
- ✅ Tampilan mobile-friendly

### 🌟 Fitur Khusus
- ✅ **Responsive Design** - Tampilan optimal di semua perangkat
- ✅ **Multi-Role Access** - Admin, Guru, Siswa
- ✅ **Target Kelas** - Tugas/Kuis bisa ditargetkan ke kelas tertentu
- ✅ **Kuis Interaktif** - Dengan timer, randomize soal, dan auto-grading
- ✅ **Import Excel** - Import data siswa & guru dari Excel
- ✅ **Notifikasi Real-time** - Flash messages untuk feedback

---

## 🛠 Teknologi

| Backend | Frontend | Database | Tools |
|---------|----------|----------|-------|
| Laravel 11 | Vue.js 3 | PostgreSQL | Vite |
| PHP 8.2+ | Inertia.js | - | Tailwind CSS |
| Eloquent ORM | Composition API | - | Heroicons |

---

## 📦 Persyaratan Sistem

- **PHP** >= 8.2
- **Composer** >= 2.0
- **Node.js** >= 18.x
- **NPM** >= 9.x
- **PostgreSQL** >= 14
- **Git**

---

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/your-username/lms-sekolah.git
cd lms-sekolah
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Setup Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Konfigurasi Database

Edit file `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=lms_sekolah
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### 5. Migrasi & Seeder

```bash
# Jalankan migrasi database
php artisan migrate

# (Opsional) Jalankan seeder untuk data dummy
php artisan db:seed
```

### 6. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 7. Jalankan Server

```bash
# Terminal 1 - Laravel Server
php artisan serve

# Terminal 2 - Vite Dev Server (untuk development)
npm run dev
```

Akses aplikasi di: `http://localhost:8000`

---

## ⚙️ Konfigurasi

### Timezone

Aplikasi sudah dikonfigurasi untuk timezone Indonesia (WIB):

```php
// config/app.php
'timezone' => 'Asia/Jakarta',
```

### Storage Link

```bash
php artisan storage:link
```

### Cache

```bash
# Clear all cache
php artisan optimize:clear

# Cache configuration (production)
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📱 Penggunaan

### Login Default

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@smksyasmu.sch.id | password |
| Guru | guru@smksyasmu.sch.id | password |
| Siswa | siswa@smksyasmu.sch.id | password |

### Import Data Excel

#### Template Siswa
| Kolom | Keterangan |
|-------|------------|
| Nama | Nama lengkap siswa |
| NIS | Nomor Induk Siswa |
| Kelas | Format: 10 TPM 1, 11 TKRO 2, dst |
| Email | Email siswa (opsional) |

#### Template Guru
| Kolom | Keterangan |
|-------|------------|
| Nama | Nama lengkap guru |
| NIP | Nomor Induk Pegawai |
| Kode Guru | Kode unik guru |
| Email | Email guru |

---

## 📁 Struktur Proyek

```
lms-sekolah/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Web/
│   │   │       ├── AssignmentController.php
│   │   │       ├── CourseController.php
│   │   │       ├── DashboardController.php
│   │   │       ├── LearningMaterialController.php
│   │   │       ├── QuizController.php
│   │   │       ├── StudentController.php
│   │   │       └── TeacherController.php
│   │   └── Middleware/
│   └── Models/
│       ├── Assignment.php
│       ├── Course.php
│       ├── LearningMaterial.php
│       ├── Quiz.php
│       ├── QuizAttempt.php
│       ├── QuizQuestion.php
│       ├── Student.php
│       ├── Teacher.php
│       └── User.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── js/
│       ├── Components/
│       ├── Layouts/
│       │   └── AppLayout.vue
│       └── Pages/
│           ├── Assignments/
│           ├── Auth/
│           ├── Courses/
│           ├── Dashboard.vue
│           ├── LearningMaterials/
│           ├── Quizzes/
│           ├── Students/
│           └── Teachers/
├── routes/
│   ├── api.php
│   └── web.php
└── public/
    └── images/
        └── logo.png
```

---

## 📚 API Documentation

API documentation tersedia di file terpisah:
- [API Documentation](API_DOCUMENTATION.md)
- [API Testing Guide](API_TESTING_GUIDE.md)

### Quick API Reference

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/students` | Get all students |
| POST | `/api/students` | Create student |
| GET | `/api/teachers` | Get all teachers |
| GET | `/api/courses` | Get all courses |
| GET | `/api/assignments` | Get all assignments |
| GET | `/api/quizzes` | Get all quizzes |

---

## 📸 Screenshots

### Dashboard Admin
Dashboard lengkap dengan statistik siswa, guru, tugas, dan aktivitas terbaru.

### Dashboard Siswa
Tampilan khusus siswa dengan tugas tersedia, kuis, dan materi pembelajaran.

### Kuis Interaktif
Sistem kuis dengan timer, berbagai tipe soal, dan auto-grading.

### Mobile Responsive
Tampilan optimal di perangkat mobile dengan navigasi yang user-friendly.

---

## 🤝 Kontribusi

Kontribusi sangat diterima! Silakan ikuti langkah berikut:

1. Fork repository
2. Buat branch fitur (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

---

## 📝 Changelog

Lihat [CHANGELOG.md](CHANGELOG.md) untuk riwayat perubahan.

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

---

## 👥 Tim Pengembang

- **SMKS Yasmu Gresik** - *Development Team*

---

## 📞 Kontak

- **Website**: [smksyasmu.sch.id](https://smksyasmu.sch.id)
- **Email**: info@smksyasmu.sch.id
- **Alamat**: Gresik, Jawa Timur, Indonesia

---

<p align="center">
  Made with ❤️ by SMKS Yasmu Gresik
</p>
