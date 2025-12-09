<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Migrate existing data from teacher_id in courses table to course_teacher pivot table
     */
    public function up(): void
    {
        // Get all courses that have a teacher_id
        $courses = DB::table('courses')->whereNotNull('teacher_id')->get();
        
        foreach ($courses as $course) {
            // Check if relationship already exists
            $exists = DB::table('course_teacher')
                ->where('course_id', $course->id)
                ->where('teacher_id', $course->teacher_id)
                ->exists();
                
            if (!$exists) {
                DB::table('course_teacher')->insert([
                    'course_id' => $course->id,
                    'teacher_id' => $course->teacher_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        // Note: We keep the teacher_id column for now for backward compatibility
        // You can remove it later with another migration once everything is working
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clear the pivot table (optional, remove if you want to keep the data)
        DB::table('course_teacher')->truncate();
    }
};
