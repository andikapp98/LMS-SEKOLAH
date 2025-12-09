<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Teacher;
use App\Models\Course;

class DataGuruSeederNew extends Seeder
{
    public function run(): void
    {
        $file = storage_path('dataGuru.xlsx');
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        $teachersData = [];
        
        // Process Excel data
        for ($i = 2; $i < count($data); $i++) {
            $row = $data[$i];
            
            $kodeGuru = $row[0];
            $namaGuru = trim($row[1]);
            $mataPelajaran = $row[5] ? trim($row[5]) : null;
            
            if (empty($kodeGuru) || empty($namaGuru)) {
                continue;
            }
            
            if (!isset($teachersData[$kodeGuru])) {
                $teachersData[$kodeGuru] = [
                    'kode_guru' => $kodeGuru,
                    'nama_guru' => $namaGuru,
                    'subjects' => []
                ];
            }
            
            if ($mataPelajaran && !in_array($mataPelajaran, $teachersData[$kodeGuru]['subjects'])) {
                $teachersData[$kodeGuru]['subjects'][] = $mataPelajaran;
            }
        }

        $this->command->info('Processing ' . count($teachersData) . ' teachers from Excel...');
        $this->command->newLine();
        
        $created = 0;
        $updated = 0;
        $linkedSubjects = 0;
        $notFoundSubjects = [];

        DB::beginTransaction();
        
        try {
            foreach ($teachersData as $kode => $data) {
                // Check if teacher already exists by kode_guru OR nama
                $teacher = Teacher::where('kode_guru', $kode)
                    ->orWhere('nama', $data['nama_guru'])
                    ->first();
                
                if (!$teacher) {
                    // Create new teacher
                    $teacher = Teacher::create([
                        'kode_guru' => str_pad($kode, 2, '0', STR_PAD_LEFT),
                        'nik' => null, // Keep nik null for now
                        'nama' => $data['nama_guru'],
                        'email' => $this->generateEmail($data['nama_guru']),
                        'no_hp' => null,
                        'alamat' => null,
                        'pendidikan_terakhir' => null,
                        'mata_pelajaran' => null,
                        'status' => 'aktif',
                    ]);
                    $created++;
                    $this->command->info("✓ Created: {$data['nama_guru']} (Kode: " . str_pad($kode, 2, '0', STR_PAD_LEFT) . ")");
                } else {
                    // Update existing teacher
                    $teacher->kode_guru = str_pad($kode, 2, '0', STR_PAD_LEFT);
                    $teacher->nama = $data['nama_guru']; // Update nama to match Excel
                    $teacher->save();
                    $updated++;
                    $this->command->info("- Updated: {$data['nama_guru']} (Kode: " . str_pad($kode, 2, '0', STR_PAD_LEFT) . ")");
                }

                // Link subjects to teacher
                if (!empty($data['subjects'])) {
                    foreach ($data['subjects'] as $subjectName) {
                        // Find course by name (case-insensitive and trim whitespace)
                        $course = Course::whereRaw('LOWER(TRIM(nama_mapel)) = ?', [strtolower(trim($subjectName))])->first();
                        
                        if ($course) {
                            // Check if relationship already exists
                            $exists = DB::table('course_teacher')
                                ->where('teacher_id', $teacher->id)
                                ->where('course_id', $course->id)
                                ->exists();
                            
                            if (!$exists) {
                                DB::table('course_teacher')->insert([
                                    'teacher_id' => $teacher->id,
                                    'course_id' => $course->id,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                                $linkedSubjects++;
                                $this->command->info("  ✓ Linked: {$subjectName}");
                            } else {
                                $this->command->info("  - Already linked: {$subjectName}");
                            }
                        } else {
                            if (!in_array($subjectName, $notFoundSubjects)) {
                                $notFoundSubjects[] = $subjectName;
                            }
                            $this->command->warn("  ✗ Subject not found: {$subjectName}");
                        }
                    }
                }
                
                $this->command->newLine();
            }
            
            DB::commit();
            
            $this->command->newLine();
            $this->command->info('====================================');
            $this->command->info('Import Summary:');
            $this->command->info("- Teachers created: {$created}");
            $this->command->info("- Teachers updated: {$updated}");
            $this->command->info("- Subject relations created: {$linkedSubjects}");
            
            if (!empty($notFoundSubjects)) {
                $this->command->newLine();
                $this->command->warn('Subjects not found in database:');
                foreach (array_unique($notFoundSubjects) as $subject) {
                    $this->command->warn("  - {$subject}");
                }
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function generateEmail($nama)
    {
        $nameParts = explode(' ', strtolower($nama));
        $firstName = $nameParts[0];
        $cleanName = preg_replace('/[^a-z0-9]/', '', $firstName);
        return $cleanName . rand(100, 999) . '@smk.sch.id';
    }
}
