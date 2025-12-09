<?php

namespace App\Imports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Log;

class TeachersImport implements ToModel, WithStartRow
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
     * Map each row to Teacher model
     * 
     * Excel Format:
     * A(0): Nama Lengkap
     * B(1): NIK
     * C(2): NUPTK
     * D(3): Tempat Lahir
     * E(4): Tanggal Lahir
     * F(5): Jenis Kelamin
     * G(6): Agama
     * H(7): Alamat
     * I(8): Status Pernikahan
     * J(9): Telepon
     * K(10): Status
     * L(11): Email
     * M(12): Lembaga
     * N(13): kode guru
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
            
            // Clean nama (Kolom A)
            $nama = !empty($row[0]) ? trim($row[0]) : null;
            if (!$nama) {
                throw new \Exception("Nama wajib diisi");
            }
            
            // Clean NIK (Kolom B)
            $nik = isset($row[1]) ? trim($row[1]) : null;
            if ($nik === '' || $nik === ' ') {
                $nik = null;
            }
            
            // Clean and validate email (Kolom L = index 11)
            $email = isset($row[11]) ? trim($row[11]) : null;
            
            // Normalize empty values to null
            if ($email === '' || $email === ' ' || $email === null || strtolower($email) === 'null') {
                $email = null;
            }
            
            // If email is not null, validate format
            if ($email !== null && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Invalid email format, set to null instead of throwing error
                Log::warning("Invalid email format for teacher: {$nama}, email: {$email}");
                $email = null;
            }
            
            // Clean phone number (Kolom J = index 9)
            $noHp = isset($row[9]) ? trim(str_replace("'", "", $row[9])) : null;
            if ($noHp === '' || $noHp === ' ') {
                $noHp = null;
            }
            
            // Clean kode_guru (Kolom N = index 13)
            $kodeGuru = isset($row[13]) ? trim($row[13]) : null;
            if ($kodeGuru === '' || $kodeGuru === ' ' || strtolower($kodeGuru) === 'null') {
                $kodeGuru = null;
            }

            // Use updateOrCreate with smart matching
            // Priority: 1. kode_guru, 2. NIK, 3. nama
            $existingTeacher = null;
            
            if ($kodeGuru) {
                $existingTeacher = Teacher::where('kode_guru', $kodeGuru)->first();
            }
            
            if (!$existingTeacher && $nik) {
                $existingTeacher = Teacher::where('nik', $nik)->first();
            }
            
            if (!$existingTeacher) {
                $existingTeacher = Teacher::where('nama', $nama)->first();
            }
            
            if ($existingTeacher) {
                // Update existing
                $existingTeacher->update([
                    'kode_guru'           => $kodeGuru ?? $existingTeacher->kode_guru,
                    'nik'                 => $nik ?? $existingTeacher->nik,
                    'nama'                => $nama,
                    'email'               => $email ?? $existingTeacher->email,
                    'no_hp'               => $noHp ?? $existingTeacher->no_hp,
                    'alamat'              => !empty($row[7]) ? trim($row[7]) : $existingTeacher->alamat,
                    'status'              => !empty($row[10]) && strtolower(trim($row[10])) === 'aktif' ? 'aktif' : 'aktif',
                ]);
                $teacher = $existingTeacher;
                Log::info("Updated teacher: {$teacher->nama}" . ($teacher->kode_guru ? " (Kode: {$teacher->kode_guru})" : ""));
            } else {
                // Create new
                $teacher = Teacher::create([
                    'kode_guru'           => $kodeGuru,
                    'nik'                 => $nik,
                    'nama'                => $nama,
                    'email'               => $email,
                    'no_hp'               => $noHp,
                    'mata_pelajaran'      => null,
                    'pendidikan_terakhir' => null,
                    'alamat'              => !empty($row[7]) ? trim($row[7]) : null,
                    'status'              => !empty($row[10]) && strtolower(trim($row[10])) === 'aktif' ? 'aktif' : 'aktif',
                ]);
                Log::info("Created teacher: {$teacher->nama}" . ($teacher->kode_guru ? " (Kode: {$teacher->kode_guru})" : ""));
            }
            
            return null; // Already saved using updateOrCreate

        } catch (\Exception $e) {
            Log::error("Error importing teacher row: " . $e->getMessage());
            $this->errors[] = [
                'nama' => $row[0] ?? 'unknown',
                'nik' => $row[1] ?? 'unknown',
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
