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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->unique()->nullable()->comment('Nomor Induk Pegawai');
            $table->string('nama');
            $table->string('email')->unique()->nullable();
            $table->string('no_hp')->nullable();
            $table->string('mata_pelajaran')->nullable()->comment('Mata pelajaran yang diampu');
            $table->enum('status', ['aktif', 'non-aktif'])->default('aktif');
            $table->text('alamat')->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
