# Changelog

Semua perubahan penting pada proyek ini akan didokumentasikan di file ini.

Format berdasarkan [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
dan proyek ini mengikuti [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [1.2.0] - 2025-12-09

### Added
- ✨ **Kuis Interaktif** - Sistem kuis lengkap dengan berbagai tipe soal
  - Multiple Choice (Pilihan Ganda)
  - True/False (Benar/Salah)
  - Short Answer (Jawaban Singkat)
  - Essay
- ✨ **Target Kelas untuk Kuis** - Kuis bisa ditargetkan ke kelas tertentu
- ✨ **Timer Kuis** - Countdown timer untuk mengerjakan kuis
- ✨ **Auto-grading** - Penilaian otomatis untuk soal objektif
- ✨ **Riwayat Percobaan** - Tracking percobaan kuis siswa
- ✨ **Dashboard Siswa Baru** - Cards statistik khusus siswa (Tugas, Kuis, Materi)

### Changed
- 🔄 **Dashboard Mobile-Friendly** - Tampilan responsif untuk semua role
- 🔄 **Navigation Bar** - Tampilan compact di mobile (icon only)
- 🔄 **Welcome Banner** - Layout baru yang lebih clean
- 🔄 **Timezone** - Diubah ke Asia/Jakarta (WIB)

### Fixed
- 🐛 Perbaikan akses kuis untuk siswa berdasarkan kelas
- 🐛 Perbaikan PostgreSQL JSON query untuk filter kelas
- 🐛 Perbaikan overflow horizontal di mobile
- 🐛 Perbaikan padding dan spacing di mobile

---

## [1.1.0] - 2025-12-06

### Added
- ✨ **Multi-Mapel Guru** - Guru bisa mengajar lebih dari satu mata pelajaran
- ✨ **Target Kelas untuk Tugas** - Tugas bisa ditargetkan ke kelas tertentu
- ✨ **Otorisasi Guru** - Guru hanya akses tugas dari mapel yang diajar
- ✨ **Materi & Modul** - Upload dan download materi pembelajaran

### Changed
- 🔄 Relasi guru-mapel dari one-to-many menjadi many-to-many
- 🔄 Tampilan daftar tugas dengan badge kelas target
- 🔄 Filter tugas/materi berdasarkan kelas siswa

### Fixed
- 🐛 Perbaikan UI sidebar untuk student (hide create/edit buttons)
- 🐛 Perbaikan error 403 untuk akses siswa

---

## [1.0.0] - 2025-12-02

### Added
- ✨ **Sistem Autentikasi** - Login multi-role (Admin, Guru, Siswa)
- ✨ **Dashboard** - Dashboard dengan statistik per role
- ✨ **Manajemen Siswa** - CRUD + Import Excel
- ✨ **Manajemen Guru** - CRUD + Import Excel
- ✨ **Manajemen Mata Pelajaran** - CRUD courses
- ✨ **Manajemen Tugas** - CRUD assignments
- ✨ **Notifikasi Toast** - Flash messages untuk feedback
- ✨ **Responsive Design** - Mobile-friendly UI

### Technical
- Laravel 11 dengan Inertia.js
- Vue.js 3 Composition API
- PostgreSQL database
- TailwindCSS styling
- Vite build tool

---

## Template

```
## [X.X.X] - YYYY-MM-DD

### Added
- Fitur baru

### Changed
- Perubahan fitur existing

### Deprecated
- Fitur yang akan dihapus

### Removed
- Fitur yang dihapus

### Fixed
- Bug fixes

### Security
- Perbaikan keamanan
```
