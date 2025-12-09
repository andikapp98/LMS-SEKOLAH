# Panduan Kode Guru - Sistem Relasi Guru dan Mata Pelajaran

## 📋 Ringkasan

Sistem LMS sekarang mendukung **kode_guru** untuk membuat relasi yang lebih akurat antara guru dan mata pelajaran. Dengan `kode_guru`, sistem dapat:

1. ✅ Menyimpan kode unik untuk setiap guru di database
2. ✅ Menggunakan kode_guru untuk mencocokkan guru dengan mata pelajaran
3. ✅ Menghindari duplikasi data guru
4. ✅ Membuat relasi many-to-many antara courses dan teachers

---

## 🗂️ Struktur Database

### Tabel `teachers`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | bigint | Primary key |
| `kode_guru` | varchar | **Kode unik guru** (dari Excel) |
| `nik` | varchar | Nomor Induk Kependudukan |
| `nip` | varchar | Nomor Induk Pegawai |
| `nama` | varchar | Nama lengkap guru |
| `email` | varchar | Email guru (nullable) |
| `no_hp` | varchar | Nomor HP (nullable) |
| `alamat` | text | Alamat lengkap (nullable) |
| `mata_pelajaran` | varchar | Mata pelajaran yang diampu (nullable) |
| `status` | enum | 'aktif' atau 'non-aktif' |
| `pendidikan_terakhir` | varchar | (nullable) |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

### Tabel `courses`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | bigint | Primary key |
| `kode_mapel` | varchar | Kode mata pelajaran (unique) |
| `nama_mapel` | varchar | Nama mata pelajaran |
| `teacher_id` | bigint | Foreign key ke teachers (nullable) |
| `kelas` | varchar | Contoh: "10 TPM 1" (nullable) |
| `jam_per_minggu` | int | Default: 2 |
| `deskripsi` | text | (nullable) |
| `semester` | enum | 'ganjil' atau 'genap' |
| `tahun_ajaran` | varchar | Default: '2025/2026' |
| `status` | enum | 'aktif' atau 'non-aktif' |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

### Tabel `course_teacher` (Pivot Table)

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | bigint | Primary key |
| `course_id` | bigint | Foreign key ke courses |
| `teacher_id` | bigint | Foreign key ke teachers |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

**Unique constraint:** `(course_id, teacher_id)` - mencegah duplikasi

---

## 📥 Format Import Excel

### File: `template_upload_guru.xlsx`

| Kolom | Index | Nama Header | Contoh | Wajib? |
|-------|-------|-------------|--------|--------|
| A | 0 | Nama Lengkap | "Budi Santoso, S.Pd" | ✅ Ya |
| B | 1 | NIK | "3201234567890123" | ❌ Tidak |
| C | 2 | NUPTK | "1234567890123456" | ❌ Tidak |
| D | 3 | Tempat Lahir | "Jakarta" | ❌ Tidak |
| E | 4 | Tanggal Lahir | "1985-05-15" | ❌ Tidak |
| F | 5 | Jenis Kelamin | "Laki-laki" | ❌ Tidak |
| G | 6 | Agama | "Islam" | ❌ Tidak |
| H | 7 | Alamat | "Jl. Merdeka No. 123" | ❌ Tidak |
| I | 8 | Status Pernikahan | "Menikah" | ❌ Tidak |
| J | 9 | Telepon | "081234567890" | ❌ Tidak |
| K | 10 | Status | "aktif" | ❌ Tidak |
| L | 11 | Email | "budi@example.com" | ❌ Tidak |
| M | 12 | Lembaga | "SMK Negeri 1" | ❌ Tidak |
| N | 13 | **kode guru** | **"GR001"** | ✅ **Sangat Penting** |

### File: `dataMapel.csv`

| Kolom | Index | Nama Header | Contoh | Wajib? |
|-------|-------|-------------|--------|--------|
| 0 | 0 | KODE GURU | "GR001" | ✅ Ya |
| 1 | 1 | NAMA GURU | "Budi Santoso, S.Pd" | ❌ (Referensi) |
| 2 | 2 | MATA PELAJARAN | "Matematika" | ✅ Ya |

---

## 🔄 Cara Kerja Import

### 1. Import Guru (`TeachersImport.php`)

```php
// Prioritas matching:
1. Cari berdasarkan kode_guru (jika ada)
2. Jika tidak ada, cari berdasarkan NIK
3. Jika tidak ada, cari berdasarkan nama

// Jika ditemukan → UPDATE
// Jika tidak ditemukan → CREATE
```

**Contoh data yang diproses:**
```
Nama: "Budi Santoso, S.Pd"
NIK: "3201234567890123"
kode_guru: "GR001"
Email: "budi@example.com"
Status: "aktif"
```

### 2. Import Mata Pelajaran (`MapelImport.php`)

```php
// Langkah:
1. Baca semua baris dari CSV
2. Kelompokkan berdasarkan mata pelajaran (MATA PELAJARAN)
3. Untuk setiap mata pelajaran:
   - Cari atau buat course
   - Cari guru berdasarkan KODE GURU
   - Hubungkan guru dengan course di tabel course_teacher
```

**Contoh relasi:**

```
Mata Pelajaran: "Matematika"
├─ Guru 1: kode_guru = "GR001" → Teacher ID: 1
├─ Guru 2: kode_guru = "GR002" → Teacher ID: 2
└─ Guru 3: kode_guru = "GR003" → Teacher ID: 3

Hasil di tabel course_teacher:
- course_id: 1, teacher_id: 1
- course_id: 1, teacher_id: 2
- course_id: 1, teacher_id: 3
```

---

## 💻 Penggunaan di Controller

### Mengambil Guru dari Course

```php
// Di CourseController atau blade template
$course = Course::with('teachers')->find($id);

// Ambil semua guru yang mengajar course ini
foreach ($course->teachers as $teacher) {
    echo $teacher->kode_guru; // GR001
    echo $teacher->nama;      // Budi Santoso, S.Pd
}
```

### Mengambil Course dari Teacher

```php
// Di TeacherController
$teacher = Teacher::with('courses')->find($id);

// Ambil semua mata pelajaran yang diajar guru ini
foreach ($teacher->courses as $course) {
    echo $course->kode_mapel; // MAT-001
    echo $course->nama_mapel; // Matematika
}
```

---

## 🧪 Testing

### Tes 1: Verifikasi Kolom Database

```bash
php artisan tinker
```

```php
// Cek apakah kolom kode_guru ada
Schema::hasColumn('teachers', 'kode_guru'); // harus true

// Cek data guru
$teacher = Teacher::first();
dd($teacher->kode_guru);
```

### Tes 2: Verifikasi Relasi

```php
// Cek relasi many-to-many
$course = Course::first();
dd($course->teachers); // Collection of teachers

$teacher = Teacher::first();
dd($teacher->courses); // Collection of courses
```

### Tes 3: Import Data

```bash
# Upload file template_upload_guru.xlsx di web interface
# Kemudian upload dataMapel.csv

# Cek log
tail -f storage/logs/laravel.log
```

---

## ⚠️ Troubleshooting

### Problem 1: Guru tidak terhubung dengan mata pelajaran

**Penyebab:** `kode_guru` di Excel tidak cocok dengan data di database

**Solusi:**
```php
// Cek kode_guru di database
Teacher::select('id', 'kode_guru', 'nama')->get();

// Pastikan kode_guru di dataMapel.csv sama persis
```

### Problem 2: Duplicate entry di course_teacher

**Penyebab:** Relasi sudah ada sebelumnya

**Solusi:** Sistem sudah menggunakan `sync()` yang akan replace relasi lama

```php
// Manual fix jika perlu
$course->teachers()->sync([$teacherId1, $teacherId2]);
```

### Problem 3: Kolom kode_guru tidak ada

**Solusi:**
```bash
php artisan migrate:status
php artisan migrate
```

---

## 📊 Query Contoh

### Cari guru berdasarkan kode_guru

```php
$teacher = Teacher::where('kode_guru', 'GR001')->first();
```

### Cari semua course yang diajar guru tertentu

```php
$courses = Teacher::where('kode_guru', 'GR001')
    ->with('courses')
    ->first()
    ->courses;
```

### Cari semua guru yang mengajar mata pelajaran tertentu

```php
$teachers = Course::where('nama_mapel', 'Matematika')
    ->with('teachers')
    ->first()
    ->teachers;
```

### Statistik guru dan mata pelajaran

```php
// Guru yang mengajar berapa mata pelajaran
Teacher::withCount('courses')->get();

// Mata pelajaran yang diajar berapa guru
Course::withCount('teachers')->get();
```

---

## 🎯 Best Practices

1. **Selalu isi kode_guru** di Excel untuk identifikasi yang lebih baik
2. **Gunakan format konsisten** untuk kode_guru (contoh: GR001, GR002, dst)
3. **Import guru dulu**, baru import mata pelajaran
4. **Check log** setelah import untuk memastikan tidak ada error
5. **Gunakan relasi many-to-many** untuk fleksibilitas (1 guru bisa mengajar banyak mapel)

---

## 📝 Changelog

- **2025-12-04**: Menambahkan kolom `kode_guru` ke tabel teachers
- **2025-12-05**: Update dokumentasi dan panduan lengkap

---

## 🔗 File Terkait

- Migration: `database/migrations/2025_12_04_055259_add_kode_guru_to_teachers_table.php`
- Model Teacher: `app/Models/Teacher.php`
- Model Course: `app/Models/Course.php`
- Import Guru: `app/Imports/TeachersImport.php`
- Import Mapel: `app/Imports/MapelImport.php`
- Pivot Table: `database/migrations/2025_12_03_021700_create_course_teacher_table.php`
