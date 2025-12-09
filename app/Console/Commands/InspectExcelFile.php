<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;

class InspectExcelFile extends Command
{
    protected $signature = 'inspect:excel-file {file}';
    protected $description = 'Inspect the structure of any excel file';

    public function handle()
    {
        $filename = $this->argument('file');
        $inputFileName = storage_path($filename);
        
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
            
            // Tampilkan 15 rows pertama dengan semua kolom
            $this->newLine();
            $this->info("=== FIRST 15 ROWS ===");
            
            for ($row = 1; $row <= 15; $row++) {
                $this->newLine();
                $this->line("Row $row:");
                
                $rowData = [];
                for ($col = 'A'; $col <= 'Z'; $col++) {
                    $cellValue = $sheet->getCell($col . $row)->getFormattedValue();
                    if ($cellValue !== null && $cellValue !== '') {
                        $rowData[$col] = $cellValue;
                    }
                }
                
                if (!empty($rowData)) {
                    foreach ($rowData as $col => $value) {
                        // Truncate long values
                        $displayValue = strlen($value) > 60 ? substr($value, 0, 60) . '...' : $value;
                        $this->line("  [$col]: $displayValue");
                    }
                } else {
                    $this->line("  (empty row)");
                }
            }

        } catch (\Exception $e) {
            $this->error('Error loading file: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
    }
}
