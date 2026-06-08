<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_paths', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('area_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('objective')->nullable();
            $table->text('skills')->nullable();
            $table->text('competencies')->nullable();
            $table->text('target_audience')->nullable();
            $table->decimal('total_hours', 8, 2)->default(0);
            $table->boolean('is_sequential')->default(false);
            $table->boolean('generates_diploma')->default(true);
            $table->boolean('generates_microcredential')->default(false);
            $table->string('status')->default('borrador');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_paths');
    }
};
