<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('microcredentials', function (Blueprint $table): void {
            $table->foreignId('learning_path_id')
                ->nullable()
                ->after('activity_id')
                ->constrained()
                ->nullOnDelete();
            $table->unique(['user_id', 'learning_path_id'], 'microcredentials_user_path_unique');
        });
    }

    public function down(): void
    {
        Schema::table('microcredentials', function (Blueprint $table): void {
            $table->dropUnique('microcredentials_user_path_unique');
            $table->dropConstrainedForeignId('learning_path_id');
        });
    }
};
