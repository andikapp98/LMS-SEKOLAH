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
        // For PostgreSQL, we need to use raw SQL with explicit casting
        DB::statement('ALTER TABLE assignments ALTER COLUMN kelas TYPE json USING CASE WHEN kelas IS NULL THEN NULL ELSE json_build_array(kelas) END');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to string (take first element if it's an array)
        DB::statement('ALTER TABLE assignments ALTER COLUMN kelas TYPE varchar USING CASE WHEN kelas IS NULL THEN NULL ELSE kelas->>0 END');
    }
};
