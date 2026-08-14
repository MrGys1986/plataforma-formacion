<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('microcredentials')
            ->whereNotNull('activity_id')
            ->whereNull('learning_path_id')
            ->where('status', 'validada')
            ->update([
                'status' => 'revocada',
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        // Las insignias antiguas de cursos no deben reactivarse automáticamente.
    }
};
