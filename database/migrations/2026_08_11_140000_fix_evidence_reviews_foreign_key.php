<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evidence_reviews', function (Blueprint $table): void {
            $table->dropForeign(['evidence_id']);
            $table->foreign('evidence_id')->references('id')->on('evidences')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('evidence_reviews', function (Blueprint $table): void {
            $table->dropForeign(['evidence_id']);
            $table->foreign('evidence_id')->references('id')->on('evidence')->cascadeOnDelete();
        });
    }
};
