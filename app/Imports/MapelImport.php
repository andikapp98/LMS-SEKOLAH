<?php

namespace App\Imports;

use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MapelImport implements ToCollection, WithStartRow
{
    protected $errors = [];
    protected $stats = [
        'courses_created' => 0,
        'courses_updated' => 0,
        'relations_created' => 0,
        'teachers_not_found' => 0,
    ];

    /**
     * Start from row 2 (after header row)
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * Process collection from dataMapel.csv
     * 
     * CSV Format:
     * Column 0: KODE GURU
     * Column 1: NAMA GURU (for reference only)
     * Column 2: MATA PELAJARAN
     */
    public function collection(Collection $rows)
    {
        // First pass: collect all unique mata pelajaran
        $uniqueMapel = [];
        $mapelToTeachers = []; // mapel => [kode_guru1, kode_guru2, ...]
        
        foreach ($rows as $row) {
            $kodeGuru = isset($row[0]) ? trim($row[0]) : null;
            $mataPelajaran = isset($row[2]) ? trim($row[2]) : null;
            
            if (empty($mataPelajaran) || empty($kodeGuru)) {
                continue;
            }
            
            // Normalize name for matching
            $normalizedMapel = $this->normalizeMapelName($mataPelajaran);
            
            if (!in_array($normalizedMapel, $uniqueMapel)) {
                $uniqueMapel[] = $normalizedMapel;
            }
            
            if (!isset($mapelToTeachers[$normalizedMapel])) {
                $mapelToTeachers[$normalizedMapel] = [];
            }
            
            if (!in_array($kodeGuru, $mapelToTeachers[$normalizedMapel])) {
                $mapelToTeachers[$normalizedMapel][] = $kodeGuru;
            }
        }
        
        Log::info("Found " . count($uniqueMapel) . " unique mata pelajaran");
        
        // Second pass: create courses and assign teachers
        foreach ($uniqueMapel as $mapelName) {
            $this->processMapel($mapelName, $mapelToTeachers[$mapelName]);
        }
    }
    
    /**
     * Normalize mapel name for matching
     */
    protected function normalizeMapelName($name)
    {
        // Remove extra spaces, lowercase for comparison
        return trim(preg_replace('/\s+/', ' ', $name));
    }
    
    /**
     * Generate kode_mapel from nama_mapel
     */
    protected function generateKodeMapel($namaMapel)
    {
        // Take first letters of each word, max 3 words
        $words = explode(' ', $namaMapel);
        $code = '';
        
        for ($i = 0; $i < min(3, count($words)); $i++) {
            $code .= strtoupper(substr($words[$i], 0, 3));
        }
        
        // Add number if exists
        $existing = Course::where('kode_mapel', 'LIKE', $code . '%')->count();
        if ($existing > 0) {
            $code .= '-' . str_pad($existing + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $code .= '-001';
        }
        
        return $code;
    }
    
    /**
     * Process one mata pelajaran
     */
    protected function processMapel($namaMapel, $kodeGuruList)
    {
        try {
            // Try to find existing course by name
            $course = Course::whereRaw('LOWER(TRIM(nama_mapel)) = ?', [strtolower($namaMapel)])->first();
            
            if (!$course) {
                // Create new course
                $kodeMapel = $this->generateKodeMapel($namaMapel);
                
                $course = Course::create([
                    'kode_mapel' => $kodeMapel,
                    'nama_mapel' => $namaMapel,
                    'kelas' => null,
                    'jam_per_minggu' => 2,
                    'deskripsi' => null,
                    'semester' => 'ganjil',
                    'tahun_ajaran' => '2025/2026',
                    'status' => 'aktif',
                    'teacher_id' => null,
                ]);
                
                $this->stats['courses_created']++;
                Log::info("Created course: {$namaMapel} ({$kodeMapel})");
            } else {
                $this->stats['courses_updated']++;
                Log::info("Course already exists: {$namaMapel} ({$course->kode_mapel})");
            }
            
            // Assign teachers
            $teacherIds = [];
            foreach ($kodeGuruList as $kodeGuru) {
                $teacher = Teacher::where('kode_guru', $kodeGuru)
                    ->orWhere('nik', $kodeGuru)
                    ->first();
                
                if ($teacher) {
                    $teacherIds[] = $teacher->id;
                } else {
                    $this->stats['teachers_not_found']++;
                    Log::warning("Teacher not found with kode_guru: {$kodeGuru} for mapel: {$namaMapel}");
                }
            }
            
            if (!empty($teacherIds)) {
                // Sync teachers (replace existing)
                $course->teachers()->sync($teacherIds);
                $this->stats['relations_created'] += count($teacherIds);
                Log::info("Assigned " . count($teacherIds) . " teachers to: {$namaMapel}");
            }
            
        } catch (\Exception $e) {
            Log::error("Error processing mapel {$namaMapel}: " . $e->getMessage());
            $this->errors[] = [
                'nama_mapel' => $namaMapel,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Get import errors
     */
    public function getErrors()
    {
        return $this->errors;
    }
    
    /**
     * Get import statistics
     */
    public function getStats()
    {
        return $this->stats;
    }
}
