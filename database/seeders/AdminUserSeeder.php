<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure admin user exists and is independent
        $admin = \App\Models\User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Administrator',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
                'teacher_id' => null, // CRITICAL: Admin must NOT have teacher_id
            ]
        );

        $this->command->info('✅ Admin user created/updated successfully');
        $this->command->info('   Email: admin@test.com');
        $this->command->info('   Password: password');
        $this->command->info('   Role: ' . $admin->role);
        $this->command->info('   Teacher ID: ' . ($admin->teacher_id ?? 'NULL (Independent)'));
    }
}
