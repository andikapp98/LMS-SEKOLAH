<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;

class SimpleImportCommand extends Command
{
    protected $signature = 'students:simple-import {--clear}';
    protected $description = 'Simple import from nominatif file';

    public function handle()
    {
        $inputFileName = storage_path('UPDATE NOMINATIF 2025-2026 FIX (1).xls');
        
        if (!file_exists($inputFileName)) {
            $this->error("File not found!");
            return 1;
        }

        if ($this->option('clear')) {
            if ($this->confirm('Clear existing data?')) {
                Student::truncate();
                $this->info('Data cleared.');
            }
        }

        $this->info('Loading file...');

        try {
            $reader = IOFactory::createReader('Xls');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($inputFileName);
            $sheet = $spreadsheet->getActiveSheet();
            
            $totalRows = $sheet->getHighestRow();
            $this->info("Total rows: $totalRows");
            
            $imported = 0;
            $errors = 0;
            $currentClass = null;
            
            $bar = $this->output->createProgressBar($totalRows);
            $bar->start();

            for ($row = 1; $row <= $totalRows; $row++) {
                $bar->advance();
                
                // Get row data
                $rowData = [];
                for ($col = 0; $col <= 20; $col++) {
                    $colLetter = chr(65 + $col); // A, B, C, ...
                    $rowData[$col] = $sheet->getCell($colLetter . $row)->getValue();
                }

                // Check for class header
                if (!empty($rowData[0]) && strpos($rowData[0], 'KELAS') !== false) {
                    $currentClass = $this->extractClassName($rowData[0]);
                    continue;
                }

                // Skip empty or header rows
                if (empty($rowData[1]) || empty($rowData[3])) {
                    continue;
                }

                // Skip header text rows
                if ($rowData[0] === 'NOMOR' || $rowData[0] === 'URUT' || $rowData[0] === 'KETERANGAN DIRI SISWA') {
                    continue;
                }

                try {
                    // Process student data
                    $nis = $rowData[1];
                    $nama = $rowData[3];
                    
                    if (empty($nis) || empty($nama)) {
                        continue;
                    }

                    // Convert date
                    $tanggalLahir = null;
                    if (!empty($rowData[7]) && is_numeric($rowData[7])) {
                        try {
                            $tanggalLahir = Date::excelToDateTimeObject($rowData[7])->format('Y-m-d');
                        } catch (\Exception $e) {
                            // Skip date if conversion fails
                        }
                    }

                    // Clean phone
                    $noHp = !empty($rowData[16]) ? str_replace("'", "", trim($rowData[16])) : null;

                    // Save student
                    Student::updateOrCreate(
                        ['nis' => $nis],
                        [
                            'nisn' => $rowData[2] ?? null,
                            'nama' => $nama,
                            'jenis_kelamin' => $rowData[5] ?? null,
                            'tempat_lahir' => $rowData[6] ?? null,
                            'tanggal_lahir' => $tanggalLahir,
                            'alamat' => $rowData[18] ?? null,
                            'no_hp' => $noHp,
                            'kelas' => $currentClass ?? 'Belum Ditentukan',
                        ]
                    );

                    $imported++;

                } catch (\Exception $e) {
                    $errors++;
                }
            }

            $bar->finish();
            $this->newLine(2);
            
            $this->info("Import completed!");
            $this->info("Imported: $imported students");
            if ($errors > 0) {
                $this->warn("Errors: $errors");
            }

            return 0;

        } catch (\Exception $e) {
            $this->error('Failed: ' . $e->getMessage());
            return 1;
        }
    }

    private function extractClassName($text)
    {
        $className = str_replace('KELAS ', '', trim($text));
        $className = str_replace(['X ', 'XI ', 'XII '], ['10-', '11-', '12-'], $className);
        $className = str_replace(' ', '-', $className);
        return $className;
    }
}
