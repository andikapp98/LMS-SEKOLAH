<?php

namespace App\Services;

use App\Models\Student;
use App\Helpers\ClassHelper;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class NominatifImportService
{
    public function import($filePath)
    {
        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        
        $highestRow = $sheet->getHighestRow();
        $importedCount = 0;
        $errors = [];

        // Deteksi Kelas dari Cell A2 (misal: "KELAS X TPM 1")
        $kelasRaw = $sheet->getCell('A2')->getValue();
        $kelas = $this->parseKelas($kelasRaw);

        // Mulai dari baris 6 (asumsi data mulai di situ berdasarkan inspeksi)
        for ($row = 6; $row <= $highestRow; $row++) {
            try {
                // Baca data per kolom
                $no = $sheet->getCell('A' . $row)->getValue();
                $nisn = $sheet->getCell('B' . $row)->getValue();
                $nis = $sheet->getCell('C' . $row)->getValue();
                $nama = $sheet->getCell('D' . $row)->getValue();
                $jk = $sheet->getCell('F' . $row)->getValue();
                $tempatLahir = $sheet->getCell('G' . $row)->getValue();
                $tglLahirRaw = $sheet->getCell('H' . $row)->getValue();
                $alamat = $sheet->getCell('S' . $row)->getValue();
                $noHp = $sheet->getCell('Q' . $row)->getValue(); // Kolom Q: TELEPHONE

                // Skip jika nama kosong (baris kosong/footer)
                if (empty($nama)) {
                    continue;
                }

                // Bersihkan data
                $nis = $this->cleanString($nis);
                $nisn = $this->cleanString($nisn);
                $noHp = $this->cleanString($noHp); // Hapus tanda petik ' di depan

                // Konversi Tanggal Lahir
                $tglLahir = null;
                if (is_numeric($tglLahirRaw)) {
                    $tglLahir = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tglLahirRaw));
                }

                // Cek duplikasi NIS
                if (Student::where('nis', $nis)->exists()) {
                    $errors[] = "Baris $row: NIS $nis sudah ada.";
                    continue;
                }

                // Simpan Siswa
                Student::create([
                    'nis' => $nis,
                    'nisn' => $nisn,
                    'nama' => $nama,
                    'jenis_kelamin' => $jk,
                    'tempat_lahir' => $tempatLahir,
                    'tanggal_lahir' => $tglLahir,
                    'alamat' => $alamat,
                    'kelas' => $kelas, // Gunakan kelas dari header file
                    'no_hp' => $noHp,
                    'status' => 'aktif',
                ]);

                $importedCount++;

            } catch (\Exception $e) {
                $errors[] = "Baris $row: " . $e->getMessage();
                Log::error("Import Error Row $row: " . $e->getMessage());
            }
        }

        return [
            'count' => $importedCount,
            'errors' => $errors,
            'kelas_detected' => $kelas
        ];
    }

    private function parseKelas($raw)
    {
        if (empty($raw)) return null;
        
        // Contoh: "KELAS X TPM 1" -> "10 TPM 1"
        $raw = strtoupper($raw);
        $raw = str_replace('KELAS ', '', $raw);
        $raw = str_replace('X ', '10 ', $raw);
        $raw = str_replace('XI ', '11 ', $raw);
        $raw = str_replace('XII ', '12 ', $raw);
        
        return trim($raw);
    }

    private function cleanString($value)
    {
        if (empty($value)) return null;
        // Hapus tanda petik tunggal di awal (biasanya format Excel text)
        return ltrim((string)$value, "'");
    }
}
