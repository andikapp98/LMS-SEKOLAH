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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->integer('duration')->nullable()->comment('Duration in minutes');
            $table->integer('max_attempts')->default(1);
            $table->integer('passing_score')->default(70)->comment('Passing score in percentage');
            $table->dateTime('available_from')->nullable();
            $table->dateTime('available_until')->nullable();
            $table->boolean('randomize_questions')->default(false);
            $table->boolean('show_correct_answers')->default(true);
            $table->enum('status', ['draft', 'published', 'closed'])->default('draft');
            $table->timestamps();
            
            $table->index('course_id');
            $table->index('created_by');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
