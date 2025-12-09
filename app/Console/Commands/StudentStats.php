<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentStats extends Command
{
    protected $signature = 'students:stats';
    protected $description = 'Show student statistics';

    public function handle()
    {
        $total = Student::count();
        $this->info("Total siswa: $total");
        
        $this->newLine();
        $this->info("Distribusi per kelas:");
        
        $stats = Student::select('kelas', DB::raw('count(*) as total'))
            ->groupBy('kelas')
            ->orderBy('kelas')
            ->get();
        
        $this->table(
            ['Kelas', 'Jumlah'],
            $stats->map(function($stat) {
                return [$stat->kelas, $stat->total];
            })
        );
        
        $this->newLine();
        $this->info("Sample data (5 siswa terakhir):");
        
        $students = Student::orderBy('created_at', 'desc')->limit(5)->get();
        
        $this->table(
            ['NIS', 'Nama', 'Kelas', 'JK', 'Tempat Lahir'],
            $students->map(function($s) {
                return [
                    $s->nis,
                    substr($s->nama, 0, 25),
                    $s->kelas,
                    $s->jenis_kelamin,
                    substr($s->tempat_lahir ?? '-', 0, 15)
                ];
            })
        );
        
        return 0;
    }
}
