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
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->integer('attempt_number')->default(1);
            $table->dateTime('started_at');
            $table->dateTime('submitted_at')->nullable();
            $table->integer('score')->nullable()->comment('Score in percentage');
            $table->integer('points_earned')->nullable();
            $table->integer('total_points')->nullable();
            $table->json('answers')->comment('Student answers in JSON format');
            $table->enum('status', ['in_progress', 'submitted', 'graded'])->default('in_progress');
            $table->timestamps();
            
            $table->index('quiz_id');
            $table->index('student_id');
            $table->index(['quiz_id', 'student_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};
