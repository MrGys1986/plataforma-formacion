<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('activity_type_id')->constrained();
            $table->foreignId('area_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('instructor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('general_objective')->nullable();
            $table->text('specific_objectives')->nullable();
            $table->text('skills')->nullable();
            $table->text('competencies')->nullable();
            $table->string('modality')->default('presencial');
            $table->string('language')->default('Español');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('schedule')->nullable();
            $table->decimal('duration_hours', 8, 2);
            $table->unsignedInteger('min_capacity')->nullable();
            $table->unsignedInteger('max_capacity')->nullable();
            $table->decimal('cost', 12, 2)->default(0);
            $table->boolean('is_external')->default(false);
            $table->boolean('requires_approval')->default(true);
            $table->boolean('requires_payment')->default(false);
            $table->boolean('requires_evaluation')->default(false);
            $table->boolean('requires_survey')->default(true);
            $table->boolean('generates_certificate')->default(true);
            $table->boolean('generates_microcredential')->default(false);
            $table->text('approval_criteria')->nullable();
            $table->string('status')->default('borrador');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
