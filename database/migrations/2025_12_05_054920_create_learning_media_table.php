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
        Schema::create('learning_media', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['modul', 'video', 'audio', 'dokumen', 'presentasi', 'link', 'lainnya'])->default('dokumen');
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_type')->nullable();
            $table->integer('file_size')->nullable(); // in bytes
            $table->string('url')->nullable(); // for external links
            $table->json('kelas')->nullable(); // target classes
            $table->enum('visibility', ['public', 'private'])->default('public');
            $table->integer('download_count')->default(0);
            $table->timestamps();
            
            // Add indexes for better query performance
            $table->index('course_id');
            $table->index('uploaded_by');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_media');
    }
};
