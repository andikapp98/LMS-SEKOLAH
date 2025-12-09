# Format Template Upload Mata Pelajaran

## Kolom Excel yang Diperlukan

File Excel harus memiliki kolom-kolom berikut (baris pertama adalah header):

| Kolom | Nama Header | Keterangan | Wajib | Format/Nilai yang Diterima |
|-------|-------------|------------|-------|---------------------------|
| A | Kode Mapel | Kode unik mata pelajaran | Ya | Contoh: MAT-001, BIN-001 |
| B | Nama Mapel | Nama mata pelajaran | Ya | Contoh: Matematika, Bahasa Indonesia |
| C | Kelas | Kelas yang diajar | Tidak | Contoh: 10 TPM 1, 11 TKJ 2 |
| D | Jam Per Minggu | Jumlah jam pelajaran per minggu | Tidak | Angka 1-10 (default: 2) |
| E | Deskripsi | Deskripsi mata pelajaran | Tidak | Text bebas |
| F | Semester | Semester mata pelajaran | Tidak | "ganjil" atau "genap" (default: ganjil) |
| G | Tahun Ajaran | Tahun ajaran | Tidak | Contoh: 2025/2026 (default: 2025/2026) |
| H | Status | Status mata pelajaran | Tidak | "aktif" atau "non-aktif" (default: aktif) |
| I | NIK/Kode Guru | NIK atau Kode Guru pengampu | Tidak | NIK (16 digit) atau Kode Guru |

## Contoh Data Excel

```
Kode Mapel | Nama Mapel          | Kelas      | Jam Per Minggu | Deskripsi                | Semester | Tahun Ajaran | Status | NIK/Kode Guru
MAT-001    | Matematika          | 10 TPM 1   | 4              | Matematika Dasar         | ganjil   | 2025/2026    | aktif  | 3524010101900001
BIN-001    | Bahasa Indonesia    | 10 TPM 1   | 3              |                          | ganjil   | 2025/2026    | aktif  | GURU-001
ING-001    | Bahasa Inggris      | 10 TPM 1   | 2              | English Basic            | ganjil   | 2025/2026    | aktif  | 3524010101900002
FIS-001    | Fisika              | 10 TPM 1   | 3              | Fisika Dasar             | ganjil   | 2025/2026    | aktif  |
KIM-001    | Kimia               | 10 TPM 1   | 3              | Kimia Dasar              | ganjil   | 2025/2026    | aktif  |
SEJ-001    | Sejarah Indonesia   | 10 TPM 1   | 2              |                          | ganjil   | 2025/2026    | aktif  |
PKN-001    | PKN                 | 10 TPM 1   | 2              | Pendidikan Kewarganegaraan | ganjil  | 2025/2026    | aktif  |
```

## Catatan Penting

1. **Update Data**: Jika Kode Mapel sudah ada di database, data akan diupdate dengan data baru dari Excel
2. **Kolom Wajib**: Kolom Kode Mapel dan Nama Mapel harus diisi
3. **Semester**: Hanya menerima nilai "ganjil" atau "genap" (huruf kecil)
4. **Status**: Hanya menerima nilai "aktif" atau "non-aktif" (huruf kecil)
5. **Jam Per Minggu**: Harus berupa angka antara 1-10
6. **Format File**: Harus berformat .xls atau .xlsx
7. **Ukuran Maksimal**: 5 MB
8. **NIK/Kode Guru**: Bisa digunakan NIK (16 digit) atau Kode Guru. Sistem akan mencari guru berdasarkan kedua field tersebut
9. **Assign Guru**: Jika NIK/Kode Guru diisi dan guru ditemukan, guru akan otomatis di-assign ke mapel. Jika tidak diisi atau guru tidak ditemukan, mapel akan dibuat tanpa guru pengampu

## Tips
- Pastikan tidak ada spasi berlebih di awal atau akhir data
- Gunakan format yang konsisten untuk semua baris
- Backup data sebelum melakukan import
- Periksa log error jika ada baris yang gagal diimport
