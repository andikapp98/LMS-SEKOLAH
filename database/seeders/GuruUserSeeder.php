<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GuruUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Membuat user untuk setiap guru yang ada di database
     * Email: dari data guru atau dibuat otomatis dari nama
     * Password default: password123
     */
    public function run(): void
    {
        $teachers = Teacher::all();
        $created = 0;
        $skipped = 0;

        foreach ($teachers as $teacher) {
            // Generate email jika tidak ada
            $email = $teacher->email;
            
            if (empty($email)) {
                // Buat email dari nama (lowercase, replace space dengan dot)
                $emailName = Str::slug(Str::lower($teacher->nama), '.');
                $email = $emailName . '@smksyasmu.sch.id';
            }

            // Cek apakah user dengan email ini sudah ada
            if (User::where('email', $email)->exists()) {
                $this->command->warn("User dengan email {$email} sudah ada. Dilewati.");
                $skipped++;
                continue;
            }

            // Buat user baru
            User::create([
                'name' => $teacher->nama,
                'email' => $email,
                'password' => Hash::make('password123'), // Password default
                'role' => 'teacher',
                'teacher_id' => $teacher->id,
            ]);

            $this->command->info("✓ User berhasil dibuat: {$teacher->nama} ({$email})");
            $created++;
        }

        $this->command->newLine();
        $this->command->info("========================================");
        $this->command->info("Total user guru yang dibuat: {$created}");
        $this->command->info("Total dilewati (sudah ada): {$skipped}");
        $this->command->info("========================================");
        $this->command->newLine();
        $this->command->warn("Password default untuk semua user: password123");
    }
}
