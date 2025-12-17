<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class NominatifStudentsImport implements ToModel, WithStartRow, WithEvents
{
    protected $currentClass = null;
    protected $errors = [];
    protected $sheet = null;

    /**
     * Start from row 6 (after headers in rows 1-5)
     */
    public function startRow(): int
    {
        return 6;
    }

    /**
     * Register events to read the class name from row 1
     */
    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $this->sheet = $event->sheet->getDelegate();
                
                // Read class name from row 1, column A (KELAS X TPM 2)
                $classCell = $this->sheet->getCell('A1')->getValue();
                
                if ($classCell && stripos($classCell, 'KELAS') !== false) {
                    $this->currentClass = $this->extractClassName($classCell);
                    Log::info("Detected class from Excel: {$this->currentClass}");
                }
            },
        ];
    }

    /**
     * Map each row to Student model
     */
    public function model(array $row)
    {
        // Use default if class not detected
        if ($this->currentClass === null) {
            $this->currentClass = '10 TPM 1'; // Default
        }

        // Skip empty rows
        if (empty($row[1]) && empty($row[3])) {
            return null;
        }

        // Skip if this is still a header row (check if column B is numeric)
        if (!is_numeric($row[1])) {
            return null;
        }

        try {
            // Convert Excel date to PHP date
            $tanggalLahir = null;
            if (!empty($row[7])) {
                try {
                    // Try to parse date (format: "17 April 2010" or Excel serial)
                    if (is_numeric($row[7])) {
                        $tanggalLahir = Date::excelToDateTimeObject($row[7])->format('Y-m-d');
                    } else {
                        // Parse text date format
                        $tanggalLahir = date('Y-m-d', strtotime($row[7]));
                    }
                } catch (\Exception $e) {
                    Log::warning("Date conversion failed for row NIS {$row[1]}: " . $e->getMessage());
                }
            }

            // Clean phone number (remove leading apostrophe and spaces)
            $noHp = !empty($row[16]) ? trim(str_replace("'", "", $row[16])) : null;

            // Use extracted class
            $kelas = $this->currentClass;

            // Column B (Nomor Daftar/PPDB) is skipped - not stored in database
            // Get NIS from column C (Nomor Induk = Nomor Induk Sekolah)
            $nis = $row[2] ?? null;
            if (empty($nis)) {
                Log::warning("Skipping row with empty NIS at column C");
                return null;
            }

            // Use updateOrCreate to handle duplicates
            $student = Student::updateOrCreate(
                ['nis' => $nis], // Find by NIS (Nomor Induk Sekolah from Column C)
                [
                    'nisn'          => null,             // NISN not available in this format
                    'nama'          => $row[3] ?? null,  // Column D: Nama Lengkap
                    'jenis_kelamin' => $row[5] ?? null,  // Column F: L/P
                    'tempat_lahir'  => $row[6] ?? null,  // Column G: Tempat Lahir
                    'tanggal_lahir' => $tanggalLahir,    // Column H: Tanggal Lahir
                    'alamat'        => $row[18] ?? null, // Column S: Alamat
                    'no_hp'         => $noHp,            // Column Q: Telephone
                    'kelas'         => $kelas,
                    'email'         => null,
                    'nama_wali'     => null,
                    'no_hp_wali'    => null,
                ]
            );

            Log::info("Imported/Updated student: {$student->nama} ({$student->nis}) - Class: {$kelas}");
            
            // Create User account for the student
            // Email format: nis@siswa.smkyasmu.sch.id
            // Password default: NIS
            $studentEmail = $nis . '@siswa.smkyasmu.sch.id';
            $studentName = $row[3] ?? 'Siswa ' . $nis;
            
            $user = User::updateOrCreate(
                ['email' => $studentEmail],
                [
                    'name' => $studentName,
                    'password' => Hash::make($nis), // Default password = NIS
                    'role' => 'student',
                ]
            );
            
            // Update student email to match user email
            $student->update(['email' => $studentEmail]);
            
            Log::info("Created/Updated user account for student: {$studentName} (Email: {$studentEmail})");
            
            // Return null because we already saved using updateOrCreate
            return null;

        } catch (\Exception $e) {
            Log::error("Error importing row NIS {$row[1]}: " . $e->getMessage());
            $this->errors[] = [
                'nis' => $row[1] ?? 'unknown',
                'nama' => $row[3] ?? 'unknown',
                'error' => $e->getMessage()
            ];
            return null;
        }
    }

    /**
     * Extract and convert class name
     * Converts "KELAS X TPM 2" to "10 TPM 2"
     */
    private function extractClassName($text)
    {
        $text = trim($text);
        
        // Remove "KELAS" prefix
        $className = str_replace('KELAS ', '', $text);
        $className = trim($className);
        
        // Convert Roman numerals to Arabic numbers at the beginning
        // X TPM 2 -> 10 TPM 2
        // XI TPM 1 -> 11 TPM 1  
        // XII TPM 3 -> 12 TPM 3
        if (preg_match('/^(XII|XI|X)\s+(.+)/', $className, $matches)) {
            $conversions = [
                'XII' => '12',
                'XI' => '11',
                'X' => '10'
            ];
            $className = $conversions[$matches[1]] . ' ' . $matches[2];
        }
        
        return $className;
    }

    /**
     * Set the class name for this import (manual override)
     */
    public function setClass($className)
    {
        $this->currentClass = $className;
    }

    /**
     * Get import errors
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
