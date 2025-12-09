<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;

class InspectExcel extends Command
{
    protected $signature = 'inspect:excel';
    protected $description = 'Inspect the structure of the nominatif excel file';

    public function handle()
    {
        $inputFileName = storage_path('UPDATE NOMINATIF 2025-2026 FIX (1).xls');
        
        $this->info("Inspecting file: $inputFileName");

        if (!file_exists($inputFileName)) {
            $this->error("File not found!");
            return;
        }

        try {
            // Identifikasi tipe file
            $inputFileType = IOFactory::identify($inputFileName);
            $this->info("File Type: " . $inputFileType);

            // Buat reader
            $reader = IOFactory::createReader($inputFileType);
            $reader->setReadDataOnly(true);
            
            // Load file
            $spreadsheet = $reader->load($inputFileName);
            $sheet = $spreadsheet->getActiveSheet();
            
            $this->info("Total Rows: " . $sheet->getHighestRow());
            $this->info("Total Columns: " . $sheet->getHighestColumn());
            
            // Tampilkan 10 rows pertama dengan semua kolom
            $this->newLine();
            $this->info("=== FIRST 10 ROWS (Showing columns A-T) ===");
            
            for ($row = 1; $row <= 10; $row++) {
                $this->newLine();
                $this->line("Row $row:");
                
                $rowData = [];
                for ($col = 'A'; $col <= 'T'; $col++) {
                    $cellValue = $sheet->getCell($col . $row)->getValue();
                    if ($cellValue !== null && $cellValue !== '') {
                        $rowData[$col] = $cellValue;
                    }
                }
                
                if (!empty($rowData)) {
                    foreach ($rowData as $col => $value) {
                        // Truncate long values
                        $displayValue = strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value;
                        $this->line("  [$col]: $displayValue");
                    }
                } else {
                    $this->line("  (empty row)");
                }
            }

        } catch (\Exception $e) {
            $this->error('Error loading file: ' . $e->getMessage());
        }
    }
}
