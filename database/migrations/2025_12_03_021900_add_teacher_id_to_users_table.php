<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add teacher_id to link user account with teacher profile
            if (!Schema::hasColumn('users', 'teacher_id')) {
                $table->foreignId('teacher_id')->nullable()->after('id')->constrained('teachers')->onDelete('cascade');
            }
            
            // Role column already exists in the table, so we skip it
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropColumn('teacher_id');
        });
    }
};
