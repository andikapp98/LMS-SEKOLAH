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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['multiple_choice', 'true_false', 'short_answer', 'essay']);
            $table->text('question_text');
            $table->text('explanation')->nullable()->comment('Feedback/explanation after answer');
            $table->integer('points')->default(1);
            $table->integer('order')->default(0);
            $table->json('options')->nullable()->comment('For MCQ and True/False');
            $table->text('correct_answer')->nullable();
            $table->timestamps();
            
            $table->index('quiz_id');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
