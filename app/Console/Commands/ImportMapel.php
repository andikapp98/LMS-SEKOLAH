<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Imports\MapelImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class ImportMapel extends Command
{
    protected $signature = 'import:mapel {file?}';
    protected $description = 'Import mata pelajaran from dataMapel.csv and assign to teachers';

    public function handle()
    {
        $file = $this->argument('file') ?? storage_path('dataMapel.xlsx');
        
        if (!file_exists($file)) {
            $this->error("File not found: {$file}");
            return 1;
        }
        
        $this->info("Importing from: {$file}");
        $this->info('');
        
        try {
            DB::beginTransaction();
            
            $import = new MapelImport();
            Excel::import($import, $file);
            
            $stats = $import->getStats();
            $errors = $import->getErrors();
            
            DB::commit();
            
            $this->info('');
            $this->info('====================================');
            $this->info('Import Summary:');
            $this->info("- Courses created: {$stats['courses_created']}");
            $this->info("- Courses updated: {$stats['courses_updated']}");
            $this->info("- Teacher relations created: {$stats['relations_created']}");
            $this->info("- Teachers not found: {$stats['teachers_not_found']}");
            
            if (count($errors) > 0) {
                $this->warn('');
                $this->warn('Errors encountered:');
                foreach ($errors as $error) {
                    $this->warn("  - {$error['nama_mapel']}: {$error['error']}");
                }
            }
            
            $this->info('');
            $this->info('✅ Import completed successfully!');
            
            return 0;
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Import failed: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}
