<?php

namespace App\Imports;

use App\Models\Course;
use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Log;

class CoursesImport implements ToModel, WithStartRow
{
    protected $errors = [];

    /**
     * Start from row 2 (after header row)
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * Map each row to Course model
     * 
     * Excel Format:
     * A(0): Kode Mapel
     * B(1): Nama Mapel
     * C(2): Kelas (contoh: 10 TPM 1)
     * D(3): Jam Per Minggu
     * E(4): Deskripsi
     * F(5): Semester (ganjil/genap)
     * G(6): Tahun Ajaran (contoh: 2025/2026)
     * H(7): Status (aktif/non-aktif)
     * I(8): NIK/Kode Guru (optional, untuk assign guru pengampu - bisa NIK atau Kode Guru)
     */
    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row[0]) && empty($row[1])) {
            return null;
        }

        try {
            // Convert all fields to string first
            $row = array_map(function($value) {
                return $value !== null ? (string)$value : null;
            }, $row);
            
            // Clean and validate kode_mapel (Kolom A)
            $kodeMapel = !empty($row[0]) ? trim($row[0]) : null;
            if (!$kodeMapel) {
                throw new \Exception("Kode Mapel wajib diisi");
            }
            
            // Clean nama_mapel (Kolom B)
            $namaMapel = !empty($row[1]) ? trim($row[1]) : null;
            if (!$namaMapel) {
                throw new \Exception("Nama Mapel wajib diisi");
            }
            
            // Clean kelas (Kolom C)
            $kelas = isset($row[2]) ? trim($row[2]) : null;
            if ($kelas === '' || $kelas === ' ') {
                $kelas = null;
            }
            
            // Clean jam_per_minggu (Kolom D)
            $jamPerMinggu = isset($row[3]) && is_numeric($row[3]) ? (int)$row[3] : 2;
            if ($jamPerMinggu < 1 || $jamPerMinggu > 10) {
                $jamPerMinggu = 2; // Default
            }
            
            // Clean deskripsi (Kolom E)
            $deskripsi = isset($row[4]) ? trim($row[4]) : null;
            if ($deskripsi === '' || $deskripsi === ' ') {
                $deskripsi = null;
            }
            
            // Clean semester (Kolom F)
            $semester = isset($row[5]) ? strtolower(trim($row[5])) : 'ganjil';
            if (!in_array($semester, ['ganjil', 'genap'])) {
                $semester = 'ganjil'; // Default
            }
            
            // Clean tahun_ajaran (Kolom G)
            $tahunAjaran = isset($row[6]) ? trim($row[6]) : '2025/2026';
            
            // Clean status (Kolom H)
            $status = isset($row[7]) ? strtolower(trim($row[7])) : 'aktif';
            if (!in_array($status, ['aktif', 'non-aktif'])) {
                $status = 'aktif'; // Default
            }
            
            // Clean NIK/Kode Guru (Kolom I) - optional
            $identifierGuru = isset($row[8]) ? trim($row[8]) : null;
            if ($identifierGuru === '' || $identifierGuru === ' ') {
                $identifierGuru = null;
            }

            // Use updateOrCreate to handle duplicates
            $course = Course::updateOrCreate(
                ['kode_mapel' => $kodeMapel], // Find by kode_mapel
                [
                    'nama_mapel'     => $namaMapel,
                    'kelas'          => $kelas,
                    'jam_per_minggu' => $jamPerMinggu,
                    'deskripsi'      => $deskripsi,
                    'semester'       => $semester,
                    'tahun_ajaran'   => $tahunAjaran,
                    'status'         => $status,
                    'teacher_id'     => null, // Legacy field, not used for many-to-many
                ]
            );
            
            // Assign teacher if NIK/Kode Guru provided
            if ($identifierGuru) {
                // Try to find by kode_guru first, then NIK
                $teacher = \App\Models\Teacher::where('kode_guru', $identifierGuru)
                    ->orWhere('nik', $identifierGuru)
                    ->first();
                    
                if ($teacher) {
                    // Sync teacher to course (replace existing)
                    $course->teachers()->sync([$teacher->id]);
                    Log::info("Assigned teacher {$teacher->nama} (NIK: {$teacher->nik}) to course {$course->nama_mapel}");
                } else {
                    Log::warning("Teacher with NIK/Kode Guru '{$identifierGuru}' not found for course {$course->nama_mapel}");
                }
            }

            Log::info("Imported/Updated course: {$course->nama_mapel} ({$course->kode_mapel})");
            
            return null; // Already saved using updateOrCreate

        } catch (\Exception $e) {
            Log::error("Error importing course row: " . $e->getMessage());
            $this->errors[] = [
                'kode_mapel' => $row[0] ?? 'unknown',
                'nama_mapel' => $row[1] ?? 'unknown',
                'error' => $e->getMessage()
            ];
            return null;
        }
    }

    /**
     * Get import errors
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
