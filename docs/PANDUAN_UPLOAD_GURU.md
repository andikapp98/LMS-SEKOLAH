# Panduan Upload Data Guru dengan Kode Guru (Excel & CSV)

## Ringkasan Perubahan

Sistem import guru telah diperbarui untuk mendukung:
1. **Kode Guru** - untuk mapping guru dengan mata pelajaran yang diampu
2. **File CSV** - selain file Excel (.xls, .xlsx)

## Format File yang Didukung

| Format | Extension | Keterangan |
|--------|-----------|------------|
| Excel 97-2003 | `.xls` | Legacy Excel format |
| Excel 2007+ | `.xlsx` | Modern Excel format |
| **CSV** | `.csv` | **Comma-Separated Values** |

**Maksimal ukuran file: 5 MB**

## Struktur Template (Excel & CSV)

File template tersedia di:
- **Excel**: `storage/template_guru.xlsx`
- **CSV**: `storage/template_guru.csv`

Kedua template memiliki **14 kolom** yang sama:

| Kolom | Nama Kolom | Keterangan | Wajib? |
|-------|------------|------------|---------|
| A/1 | Nama Lengkap | Nama lengkap guru | ✅ Ya |
| B/2 | NIK | Nomor Induk Kependudukan | ❌ Tidak |
| C/3 | NUPTK | Nomor Unik Pendidik dan Tenaga Kependidikan | ❌ Tidak |
| D/4 | Tempat Lahir | Tempat lahir guru | ❌ Tidak |
| E/5 | Tanggal Lahir | Format: YYYY-MM-DD (contoh: 1985-05-15) | ❌ Tidak |
| F/6 | Jenis Kelamin | Laki-laki / Perempuan | ❌ Tidak |
| G/7 | Agama | Agama guru | ❌ Tidak |
| H/8 | Alamat | Alamat lengkap | ❌ Tidak |
| I/9 | Status Pernikahan | Menikah / Belum Menikah | ❌ Tidak |
| J/10 | Telepon | Nomor telepon/HP | ❌ Tidak |
| K/11 | Status | Aktif / Tidak Aktif | ❌ Tidak (default: Aktif) |
| L/12 | Email | Email guru (harus format valid) | ❌ Tidak |
| M/13 | Lembaga | Nama lembaga/sekolah | ❌ Tidak |
| **N/14** | **Kode Guru** | **Kode unik untuk identifikasi guru** | ⚠️ **Sangat Disarankan** |

## 🔴 PENTING: Kolom Kode Guru (Kolom N/14)

### Kenapa Kode Guru Penting?

Kode Guru digunakan untuk:
1. **Mapping guru dengan mata pelajaran** - Agar mata pelajaran bisa terhubung dengan guru pengampunya
2. **Identifikasi unik** - Menghindari data duplikat saat import ulang
3. **Update data** - Saat upload ulang, sistem akan update data guru berdasarkan kode_guru

### Format Kode Guru

- **Bebas, asalkan unik** untuk setiap guru
- Contoh format yang disarankan:
  - `GRU001`, `GRU002`, `GRU003`, ... (Guru 001, 002, 003)
  - `MTK01`, `BIN01`, `IPA01`, ... (singkatan mapel + nomor)
  - `AF001`, `SN001`, ... (inisial nama + nomor)

## Format File CSV

### Aturan Format CSV:
1. **Delimiter**: Koma (`,`)
2. **Text Qualifier**: Tanda kutip ganda (`"`) untuk field yang mengandung koma atau newline
3. **Encoding**: UTF-8 (untuk support karakter Indonesia)
4. **Baris Pertama**: Header kolom
5. **Escape**: Gunakan `""` (double quote) untuk quote di dalam field

### Contoh Data CSV:

```csv
Nama Lengkap,NIK,NUPTK,Tempat Lahir,Tanggal Lahir,Jenis Kelamin,Agama,Alamat,Status Pernikahan,Telepon,Status,Email,Lembaga,Kode Guru
Ahmad Fauzi,3201234567890123,1234567890123456,Jakarta,1985-05-15,Laki-laki,Islam,"Jl. Pendidikan No. 123, Jakarta",Menikah,081234567890,Aktif,ahmad.fauzi@sekolah.sch.id,SMK,GRU001
Siti Nurhaliza,3201234567890124,1234567890123457,Bandung,1990-03-20,Perempuan,Islam,"Jl. Raya No. 45",Menikah,082345678901,Aktif,siti.n@sekolah.sch.id,SMK,GRU002
```

### Tips untuk CSV:
- Gunakan Excel/LibreOffice untuk membuat CSV, lalu "Save As" → CSV (UTF-8)
- Jika menggunakan text editor, pastikan encoding UTF-8
- Field dengan koma atau quote harus dibungkus dengan quote (`"`)
- Contoh: `"Jl. Merdeka No. 123, Jakarta"` (karena ada koma)

## Cara Upload Data Guru

### 1. Download Template

**Excel:**
- File: `storage/template_guru.xlsx`
- Rekomendasi: Untuk data besar atau kompleks

**CSV:**
- File: `storage/template_guru.csv`
- Rekomendasi: Untuk integrasi dengan sistem lain atau export dari aplikasi lain

### 2. Isi Data Guru

#### Menggunakan Excel:
1. Buka `template_guru.xlsx` dengan Microsoft Excel atau LibreOffice Calc
2. Mulai isi data dari **baris ke-2** (baris 1 adalah header)
3. **WAJIB** isi kolom **A (Nama Lengkap)**
4. **SANGAT DISARANKAN** isi kolom **N (Kode Guru)**
5. Save sebagai `.xlsx` atau export ke `.csv`

#### Menggunakan CSV:
1. Buka `template_guru.csv` dengan text editor atau Excel
2. Tambahkan baris baru setelah header
3. Pisahkan setiap field dengan koma
4. Field dengan koma harus dibungkus quote: `"teks, dengan koma"`
5. Save dengan encoding UTF-8

### 3. Upload File

1. Login sebagai **Admin**
2. Buka menu **Teachers**
3. Klik tombol **Upload Guru**
4. Pilih file Excel (`.xls`, `.xlsx`) atau CSV (`.csv`)
5. Upload

### 4. Sistem Import

Sistem akan:
1. **Cek kode_guru** - Jika ada guru dengan kode_guru yang sama, akan **update** data guru
2. **Cek NIK** - Jika tidak ada kode_guru, cek NIK
3. **Cek nama** - Jika tidak ada kode_guru dan NIK, cek nama
4. **Create baru** - Jika tidak ditemukan, buat data guru baru

## Perbandingan Excel vs CSV

| Aspek | Excel (.xlsx) | CSV (.csv) |
|-------|---------------|------------|
| **Ukuran File** | Lebih besar | Lebih kecil |
| **Kompatibilitas** | Perlu software Excel/LibreOffice | Bisa dibuka dengan text editor |
| **Formatting** | Support formatting, formula | Plain text only |
| **Ease of Use** | Lebih mudah untuk edit manual | Mudah untuk automation |
| **Integrasi** | Bagus untuk manual entry | Bagus untuk export dari sistem lain |
| **Error Handling** | Lebih baik (type checking) | Manual (semua string) |

**Rekomendasi:**
- 📊 **Gunakan Excel** jika input manual atau data kompleks
- 📄 **Gunakan CSV** jika export dari sistem lain atau automation

## Update Database (Untuk Developer)

### Migration
```php
$table->string('kode_guru')->unique()->nullable()->after('id');
```

### Model Teacher
```php
protected $fillable = [
    'kode_guru', // BARU!
    'nik',
    'nama',
    'email',
    'no_hp',
    'mata_pelajaran',
    'status',
    'alamat',
    'pendidikan_terakhir',
];
```

### Import Support
- **TeachersImport** class support Excel & CSV
- Laravel Excel package otomatis handle kedua format
- Validation: `mimes:xls,xlsx,csv` (max 5MB)

## Troubleshooting

### Masalah: CSV tidak ter-import dengan benar

**Penyebab**: Encoding atau delimiter salah

**Solusi:**
1. Pastikan encoding UTF-8
2. Delimiter harus koma (`,`)
3. Buka dengan Excel → Save As → CSV UTF-8

### Masalah: Data dengan koma rusak

**Penyebab**: Field tidak dibungkus quote

**Solusi:**
```csv
# SALAH
Ahmad,Jl. Merdeka, Jakarta,...

# BENAR  
Ahmad,"Jl. Merdeka, Jakarta",...
```

### Masalah: Mata pelajaran tidak ada guru pengampunya

**Penyebab**: Guru belum memiliki `kode_guru`

**Solusi:**
1. Download template baru (Excel atau CSV)
2. Isi kolom **Kode Guru** untuk setiap guru
3. Upload ulang data guru

### Masalah: File CSV terlalu besar

**Solusi:**
- Split file menjadi beberapa bagian (max 5MB each)
- Atau compress data yang tidak perlu
- Atau gunakan format Excel (biasanya lebih efisien)

## Contoh Lengkap CSV

```csv
Nama Lengkap,NIK,NUPTK,Tempat Lahir,Tanggal Lahir,Jenis Kelamin,Agama,Alamat,Status Pernikahan,Telepon,Status,Email,Lembaga,Kode Guru
Ahmad Fauzi,3201234567890123,1234567890123456,Jakarta,1985-05-15,Laki-laki,Islam,"Jl. Pendidikan No. 123, Jakarta",Menikah,081234567890,Aktif,ahmad.fauzi@sekolah.sch.id,SMK,GRU001
Siti Nurhaliza,3201234567890124,1234567890123457,Bandung,1990-03-20,Perempuan,Islam,"Jl. Raya No. 45, Bandung",Menikah,082345678901,Aktif,siti.nurhaliza@sekolah.sch.id,SMK,GRU002
Budi Santoso,3201234567890125,1234567890123458,Surabaya,1988-07-10,Laki-laki,Islam,"Jl. Diponegoro 78",Menikah,083456789012,Aktif,budi.santoso@sekolah.sch.id,SMK,GRU003
```

## File Yang Sudah Diupdate

1. ✅ `storage/template_guru.xlsx` - Template Excel dengan kolom Kode Guru
2. ✅ `storage/template_guru.csv` - **Template CSV baru** dengan kolom Kode Guru
3. ✅ `app/Http/Controllers/Web/TeacherController.php` - Support CSV upload
4. ✅ `resources/js/Pages/Teachers/Upload.vue` - UI support CSV
5. ✅ `app/Imports/TeachersImport.php` - Import logic support Excel & CSV
6. ✅ `database/migrations/...add_kode_guru...` - Migration kode_guru
7. ✅ `app/Models/Teacher.php` - Model dengan field kode_guru

## API Response Format

Setelah upload, sistem akan return:

**Success:**
```json
{
    "message": "Import berhasil!",
    "success": true
}
```

**Partial Success:**
```json
{
    "message": "Import berhasil! Namun ada 2 baris yang gagal diimport.",
    "success": true
}
```

**Error:**
```json
{
    "message": "Import gagal: File format tidak valid",
    "error": true
}
```

---

**Catatan Penting:**
- Kolom **Kode Guru** sangat penting untuk mapping guru dengan mata pelajaran
- Format **CSV** dan **Excel** menggunakan struktur kolom yang sama
- Sistem otomatis detect format file (tidak perlu spesifikasi manual)
- Untuk data besar (>1000 baris), gunakan Excel yang lebih efisien
- Untuk automation/integration, gunakan CSV yang lebih simple
