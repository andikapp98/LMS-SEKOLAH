# DOKUMENTASI: Pemisahan Data Admin dan Guru

## ✅ STATUS: Admin Sudah Terpisah dari Guru

### Struktur Database

#### Users Table
- `id` - Primary key
- `name` - Nama user
- `email` - Email (unique)
- `password` - Password (hashed)
- `role` - Enum: 'admin' atau 'teacher'
- `teacher_id` - Foreign key ke teachers.id (NULLABLE, onDelete: SET NULL)

### Admin User (Independent)

**Credentials:**
- Email: `admin@test.com`
- Password: `password`
- Role: `admin`
- Teacher ID: `NULL` ✅ (TIDAK terhubung ke teachers table)

**Karakteristik:**
1. ✅ `teacher_id` = NULL (tidak ada relasi ke teachers)
2. ✅ Foreign key menggunakan `onDelete('set null')`
3. ✅ Jika semua teacher dihapus, admin TIDAK logout
4. ✅ Admin tetap bisa login dan akses sistem

### Guru Users (Linked to Teachers)

- Role: `teacher`
- Teacher ID: [ID dari teachers table]
- Jika teacher dihapus: `teacher_id` menjadi NULL, tapi user tetap ada

## 🔧 Cara Ensure Admin Independent

### 1. Run AdminUserSeeder
```bash
php artisan db:seed --class=AdminUserSeeder
```

### 2. Verify Admin
```bash
php check_admin.php
```

Output yang benar:
```
✅ Admin found:
  Name: Administrator
  Email: admin@test.com
  Role: admin
  Teacher ID: NULL (Independent)
```

## ⚠️ PENTING: Jangan Hapus Admin Manual

Jika admin terhapus, jalankan seeder lagi:
```bash
php artisan db:seed --class=AdminUserSeeder
```

## 🧪 Test Skenario: Hapus Semua Data Guru

### Langkah Test:
```bash
# 1. Login sebagai admin
# 2. Hapus semua teachers
php artisan tinker --execute="DB::table('teachers')->truncate();"

# 3. Verify admin masih bisa akses
# Admin tetap login ✅
# Admin masih bisa akses semua fitur ✅
```

### Hasil yang Diharapkan:
- ✅ Admin tetap login
- ✅ Admin masih bisa akses dashboard
- ✅ Admin masih bisa manage data
- ✅ Teacher users akan kehilangan `teacher_id` tapi tetap ada

## 📋 Migration Info

**File:** `2025_12_04_050438_fix_teacher_id_foreign_key_in_users_table.php`

Foreign key constraint:
```php
$table->foreign('teacher_id')
    ->references('id')
    ->on('teachers')
    ->onDelete('set null');  // ← KEY: SET NULL, bukan CASCADE
```

Ini memastikan ketika teacher dihapus:
- User teacher: `teacher_id` → NULL (tetap ada)
- User admin: tidak terpengaruh (sudah NULL)

## ✅ Kesimpulan

**Status**: ✅ **AMAN**
- Admin sudah terpisah dari data guru
- Admin tidak akan logout saat data guru dihapus
- System ready untuk production use
