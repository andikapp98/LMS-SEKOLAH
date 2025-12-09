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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mapel')->unique()->comment('Kode Mata Pelajaran');
            $table->string('nama_mapel');
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->onDelete('set null');
            $table->string('kelas')->nullable()->comment('Kelas yang diajar (contoh: 10 TPM 1)');
            $table->integer('jam_per_minggu')->default(2);
            $table->text('deskripsi')->nullable();
            $table->enum('semester', ['ganjil', 'genap'])->default('ganjil');
            $table->string('tahun_ajaran')->default('2025/2026');
            $table->enum('status', ['aktif', 'non-aktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
