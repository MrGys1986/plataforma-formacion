<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table): void {
            $table->string('classroom_url')->nullable()->after('schedule');
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table): void {
            $table->dropColumn('classroom_url');
        });
    }
};
