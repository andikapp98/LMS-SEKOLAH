<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Teacher;
use App\Models\Course;

class DataGuruSeeder extends Seeder
{
    public function run(): void
    {
        $file = storage_path('dataGuru.xlsx');
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        $teachersData = [];
        
        // Process Excel data - group by kode_guru FIRST
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
                    'nama_guru' => $namaGuru, // Use FIRST occurrence of name for this kode
                    'nama_variants' => [$namaGuru], // Track all name variants
                    'subjects' => []
                ];
            } else {
                // Same kode_guru but different name - track variant but don't change primary name
                if ($namaGuru !== $teachersData[$kodeGuru]['nama_guru']) {
                    if (!in_array($namaGuru, $teachersData[$kodeGuru]['nama_variants'])) {
                        $teachersData[$kodeGuru]['nama_variants'][] = $namaGuru;
                    }
                }
            }
            
            if ($mataPelajaran && !in_array($mataPelajaran, $teachersData[$kodeGuru]['subjects'])) {
                $teachersData[$kodeGuru]['subjects'][] = $mataPelajaran;
            }
        }

        $this->command->info('Processing ' . count($teachersData) . ' teachers...');
        
        // Report name inconsistencies
        $inconsistencies = array_filter($teachersData, fn($t) => count($t['nama_variants']) > 1);
        if (!empty($inconsistencies)) {
            $this->command->warn('⚠️  Found ' . count($inconsistencies) . ' teachers with name inconsistencies in CSV:');
            foreach ($inconsistencies as $kode => $data) {
                $this->command->warn("  Kode {$kode}: " . implode(' | ', $data['nama_variants']));
            }
            $this->command->warn('  → Using first variant as primary name');
            $this->command->info('');
        }
        
        $importedTeachers = 0;
        $importedRelations = 0;
        $notFoundSubjects = [];

        DB::beginTransaction();
        
        try {
            foreach ($teachersData as $kode => $data) {
                // First, try to find by kode_guru
                $teacher = Teacher::where('kode_guru', $kode)->first();
                
                // If not found by kode_guru, try to find by exact name match
                if (!$teacher) {
                    $teacher = Teacher::where('nama', $data['nama_guru'])->first();
                }
                
                if (!$teacher) {
                    // Create new teacher with kode_guru
                    $teacher = Teacher::create([
                        'kode_guru' => $kode,
                        'nik' => null, // NIK will be filled manually or from other source
                        'nama' => $data['nama_guru'], // Use exact name from CSV
                        'email' => $this->generateEmail($data['nama_guru']),
                        'no_hp' => null,
                        'alamat' => null,
                        'pendidikan_terakhir' => null,
                        'mata_pelajaran' => null,
                        'status' => 'aktif',
                    ]);
                    $importedTeachers++;
                    $this->command->info("✓ Created teacher: {$data['nama_guru']} (Kode: {$kode})");
                } else {
                    // Teacher exists - update kode_guru if not set, DON'T touch NIK
                    $updated = false;
                    
                    if (!$teacher->kode_guru || $teacher->kode_guru !== $kode) {
                        $teacher->kode_guru = $kode;
                        $updated = true;
                    }
                    
                    // Update name to match CSV exactly (for course matching)
                    if ($teacher->nama !== $data['nama_guru']) {
                        $teacher->nama = $data['nama_guru'];
                        $updated = true;
                    }
                    
                    if ($updated) {
                        $teacher->save();
                        $this->command->info("✓ Updated teacher: {$data['nama_guru']} (Kode: {$kode}, NIK preserved: {$teacher->nik})");
                    } else {
                        $this->command->info("- Teacher already up to date: {$data['nama_guru']} (Kode: {$teacher->kode_guru})");
                    }
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
                                $importedRelations++;
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
                
                $this->command->info('---');
            }
            
            DB::commit();
            
            $this->command->info('');
            $this->command->info('====================================');
            $this->command->info('Import Summary:');
            $this->command->info("- Teachers created: {$importedTeachers}");
            $this->command->info("- Subject relations created: {$importedRelations}");
            
            if (!empty($notFoundSubjects)) {
                $this->command->warn('');
                $this->command->warn('Subjects not found in database:');
                foreach ($notFoundSubjects as $subject) {
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
