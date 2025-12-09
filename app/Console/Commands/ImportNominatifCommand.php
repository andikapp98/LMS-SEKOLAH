<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Imports\NominatifStudentsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class ImportNominatifCommand extends Command
{
    protected $signature = 'students:import-nominatif {--clear : Clear existing student data before import}';
    protected $description = 'Import students from UPDATE NOMINATIF 2025-2026 FIX (1).xls file';

    public function handle()
    {
        $inputFileName = storage_path('UPDATE NOMINATIF 2025-2026 FIX (1).xls');
        
        if (!file_exists($inputFileName)) {
            $this->error("File not found: $inputFileName");
            return 1;
        }

        // Clear existing data if requested
        if ($this->option('clear')) {
            if ($this->confirm('Are you sure you want to delete all existing student data?')) {
                Student::truncate();
                $this->info('Existing student data cleared.');
            } else {
                $this->info('Import cancelled.');
                return 0;
            }
        }

        $this->info('Reading file: ' . $inputFileName);
        $this->info('Starting import...');

        try {
            DB::beginTransaction();

            $import = new NominatifStudentsImport();
            Excel::import($import, $inputFileName);

            $errors = $import->getErrors();
            
            DB::commit();

            $totalStudents = Student::count();
            $this->info("Import completed successfully!");
            $this->info("Total students in database: $totalStudents");

            if (count($errors) > 0) {
                $this->warn("Number of rows with errors: " . count($errors));
                
                if ($this->confirm('Do you want to see the errors?')) {
                    foreach ($errors as $index => $error) {
                        $this->line("Error " . ($index + 1) . ": " . $error['error']);
                    }
                }
            }

            // Show sample of imported students
            $this->newLine();
            $this->info('Sample of imported students:');
            $students = Student::orderBy('created_at', 'desc')->limit(5)->get();
            
            $this->table(
                ['NIS', 'Nama', 'Kelas', 'Jenis Kelamin', 'Tempat Lahir'],
                $students->map(function($student) {
                    return [
                        $student->nis,
                        $student->nama,
                        $student->kelas,
                        $student->jenis_kelamin,
                        $student->tempat_lahir
                    ];
                })
            );

            // Show class distribution
            $this->newLine();
            $this->info('Student distribution by class:');
            $classCounts = Student::select('kelas', DB::raw('count(*) as total'))
                ->groupBy('kelas')
                ->orderBy('kelas')
                ->get();
            
            $this->table(
                ['Kelas', 'Jumlah Siswa'],
                $classCounts->map(function($class) {
                    return [
                        $class->kelas,
                        $class->total
                    ];
                })
            );

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Import failed: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}
