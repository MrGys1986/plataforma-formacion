<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evidences', function (Blueprint $table): void {
            $table->string('evidence_type', 50)->default('otro')->change();
        });
    }

    public function down(): void
    {
        // Se conserva como texto para no perder tipos de evidencia existentes.
    }
};
