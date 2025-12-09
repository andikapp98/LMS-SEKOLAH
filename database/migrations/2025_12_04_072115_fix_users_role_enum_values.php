<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop existing check constraint
        DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check');
        
        // Add new check constraint with English values
        DB::statement("
            ALTER TABLE users 
            ADD CONSTRAINT users_role_check 
            CHECK (role IN ('admin', 'teacher', 'student'))
        ");
        
        // Update existing data: guru -> teacher, siswa -> student
        DB::statement("UPDATE users SET role = 'teacher' WHERE role = 'guru'");
        DB::statement("UPDATE users SET role = 'student' WHERE role = 'siswa'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop English constraint
        DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check');
        
        // Add back Indonesian constraint
        DB::statement("
            ALTER TABLE users 
            ADD CONSTRAINT users_role_check 
            CHECK (role IN ('admin', 'guru', 'siswa'))
        ");
        
        // Revert data
        DB::statement("UPDATE users SET role = 'guru' WHERE role = 'teacher'");
        DB::statement("UPDATE users SET role = 'siswa' WHERE role = 'student'");
    }
};
