<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder creates sample users with different roles and links teachers to their user accounts
     */
    public function run(): void
    {
        try {
            // Create admin user only if not exists
            $existingAdmin = DB::table('users')->where('email', 'admin@smksyasmu.sch.id')->first();
            
            if ($existingAdmin) {
                echo "✓ Admin user already exists (email: admin@smksyasmu.sch.id)\n";
                // Update role if needed
                if ($existingAdmin->role !== 'admin') {
                    DB::table('users')->where('id', $existingAdmin->id)->update(['role' => 'admin']);
                    echo "  Updated role to admin\n";
                }
            } else {
                try {
                    DB::table('users')->insert([
                        'name' => 'Admin SMKS YASMU',
                        'email' => 'admin@smksyasmu.sch.id',
                        'password' => Hash::make('password'),
                        'role' => 'admin',
                        'teacher_id' => null,
                        'email_verified_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    echo "✓ Admin user created (email: admin@smksyasmu.sch.id, password: password)\n";
                } catch (\Exception $e) {
                    echo "⚠ Could not create admin user: " . $e->getMessage() . "\n";
                }
            }

            // Get all teachers and create user accounts for them
            $teachers = DB::table('teachers')->where('status', 'aktif')->get();

            if ($teachers->isEmpty()) {
                echo "⚠ No active teachers found in database.\n";
            }

            foreach ($teachers as $teacher) {
                // Check if user already exists for this teacher
                $existingUser = DB::table('users')->where('teacher_id', $teacher->id)->first();
                
                if ($existingUser) {
                    echo "  User already exists for teacher: {$teacher->nama}\n";
                    continue;
                }

                // Generate email from teacher name or use existing email
                $email = $teacher->email ?? strtolower(str_replace(' ', '.', $teacher->nama)) . '@smksyasmu.sch.id';
                
                // Check if email already exists
                if (DB::table('users')->where('email', $email)->exists()) {
                    echo "  Email {$email} already exists, skipping teacher: {$teacher->nama}\n";
                    continue;
                }

                try {
                    // Create user account for teacher
                    DB::table('users')->insert([
                        'name' => $teacher->nama,
                        'email' => $email,
                        'password' => Hash::make('password123'), // Default password
                        'role' => 'teacher',
                        'teacher_id' => $teacher->id,
                        'email_verified_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    echo "✓ Created user account for teacher: {$teacher->nama} ({$email})\n";
                } catch (\Exception $e) {
                    echo "✗ Failed to create user for {$teacher->nama}: " . $e->getMessage() . "\n";
                }
            }

            echo "\n=== Seeder Summary ===\n";
            echo "Total users: " . DB::table('users')->count() . "\n";
            echo "Admin users: " . DB::table('users')->where('role', 'admin')->count() . "\n";
            echo "Teacher users: " . DB::table('users')->where('role', 'teacher')->count() . "\n";
            echo "\nDefault passwords:\n";
            echo "- Admin: password\n";
            echo "- Teachers: password123\n";
        } catch (\Exception $e) {
            echo "\n✗ Seeder failed: " . $e->getMessage() . "\n";
            echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
            throw $e;
        }
    }
}
