# QUICK FIX: Courses Without Teachers

## ❌ PROBLEM
- Database: 48 courses, 50 teachers
- Tapi `course_teacher` table KOSONG (0 relations)
- Semua course tampil "Belum ada guru"

## ✅ ROOT CAUSE
Courses dibuat **BUKAN** via `import:mapel`:
- Nama mapel di database ≠ nama di dataMapel.xlsx
- Tidak ada auto-assign teachers
- Pivot table kosong

## 🚀 SOLUTION (LANGKAH MUDAH)

### Via Web Interface (RECOMMENDED):

1. **Login sebagai admin**
2. **Menu → Mata Pelajaran**
3. **Klik "Hapus Semua Data"** (tombol merah)
4. **Konfirmasi** → "Ya, Hapus Semua"
5. **Tunggu sampai selesai**
6. **Jalankan command**:
   ```bash
   php artisan import:mapel
   ```
7. **Refresh browser**
8. ✅ **DONE!** Semua mapel akan punya guru

### Via Command Line:

```bash
# 1. Clear courses
php artisan tinker

# Dalam tinker, ketik:
Course::query()->delete();
DB::table('course_teacher')->truncate();
exit

# 2. Import mapel yang benar
php artisan import:mapel

# 3. Verify
php deep_db_check.php
```

## 📊 EXPECTED RESULT

Setelah import berhasil:
```
✅ Teachers: 50
✅ Courses: 70
✅ course_teacher relations: 119+
✅ Semua course punya guru
✅ Tidak ada "Belum ada guru"
```

## ⚠️ CATATAN PENTING

**HARUS** hapus courses yang ada dulu karena:
- Nama tidak match dengan dataMapel.xlsx
- kode_mapel salah (pakai kode_guru)
- Import tidak akan fix yang sudah ada

**JANGAN** manual create course via UI!
**SELALU** pakai `php artisan import:mapel`
