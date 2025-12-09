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
            // Drop existing foreign key with cascade
            if (Schema::hasColumn('users', 'teacher_id')) {
                $table->dropForeign(['teacher_id']);
                
                // Re-add foreign key with SET NULL instead of CASCADE
                // This prevents admin users from being deleted when teachers are deleted
                $table->foreign('teacher_id')
                    ->references('id')
                    ->on('teachers')
                    ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert back to cascade (though not recommended)
            if (Schema::hasColumn('users', 'teacher_id')) {
                $table->dropForeign(['teacher_id']);
                
                $table->foreign('teacher_id')
                    ->references('id')
                    ->on('teachers')
                    ->onDelete('cascade');
            }
        });
    }
};
