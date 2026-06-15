<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_programs', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('activity_type_id')->constrained()->restrictOnDelete();
            $table->foreignId('area_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('general_objective')->nullable();
            $table->text('specific_objectives')->nullable();
            $table->text('skills')->nullable();
            $table->string('default_modality')->default('presencial');
            $table->string('language')->default('Español');
            $table->decimal('duration_hours', 8, 2)->default(0);
            $table->decimal('default_cost', 12, 2)->default(0);
            $table->boolean('is_external')->default(false);
            $table->boolean('requires_approval')->default(true);
            $table->boolean('requires_payment')->default(false);
            $table->boolean('requires_evaluation')->default(false);
            $table->boolean('requires_survey')->default(true);
            $table->boolean('generates_certificate')->default(true);
            $table->boolean('generates_microcredential')->default(false);
            $table->text('approval_criteria')->nullable();
            $table->string('status')->default('activo');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('activities', function (Blueprint $table): void {
            $table->foreignId('training_program_id')
                ->nullable()
                ->after('id')
                ->constrained()
                ->nullOnDelete();
            $table->unsignedInteger('edition_number')->default(1)->after('training_program_id');
            $table->string('edition_code')->nullable()->after('edition_number');
            $table->date('enrollment_start_date')->nullable()->after('end_date');
            $table->date('enrollment_end_date')->nullable()->after('enrollment_start_date');
            $table->softDeletes();
            $table->unique(['training_program_id', 'edition_number'], 'activities_program_edition_unique');
        });

        DB::table('activities')
            ->orderBy('id')
            ->chunkById(100, function ($activities): void {
                foreach ($activities as $activity) {
                    $programId = DB::table('training_programs')->insertGetId([
                        'public_id' => (string) Str::ulid(),
                        'activity_type_id' => $activity->activity_type_id,
                        'area_id' => $activity->area_id,
                        'created_by' => $activity->created_by,
                        'name' => $activity->name,
                        'slug' => $activity->slug,
                        'description' => $activity->description,
                        'general_objective' => $activity->general_objective,
                        'specific_objectives' => $activity->specific_objectives,
                        'skills' => $activity->skills,
                        'default_modality' => $activity->modality,
                        'language' => $activity->language,
                        'duration_hours' => $activity->duration_hours,
                        'default_cost' => $activity->cost,
                        'is_external' => $activity->is_external,
                        'requires_approval' => $activity->requires_approval,
                        'requires_payment' => $activity->requires_payment,
                        'requires_evaluation' => $activity->requires_evaluation,
                        'requires_survey' => $activity->requires_survey,
                        'generates_certificate' => $activity->generates_certificate,
                        'generates_microcredential' => $activity->generates_microcredential,
                        'approval_criteria' => $activity->approval_criteria,
                        'status' => $activity->status === 'archivado' ? 'inactivo' : 'activo',
                        'created_at' => $activity->created_at,
                        'updated_at' => $activity->updated_at,
                    ]);

                    DB::table('activities')
                        ->where('id', $activity->id)
                        ->update([
                            'training_program_id' => $programId,
                            'edition_number' => 1,
                            'edition_code' => Str::upper(Str::slug($activity->slug)).'-E01',
                        ]);
                }
            });

        DB::table('activity_types')
            ->whereIn('name', ['Diplomado', 'Certificacion', 'Competencia'])
            ->update(['status' => 'inactivo']);

        Schema::table('learning_paths', function (Blueprint $table): void {
            $table->softDeletes();
        });

        Schema::create('competencies', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('area_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('objective')->nullable();
            $table->text('completion_criteria')->nullable();
            $table->string('status')->default('activo');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('certification_programs', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('area_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('objective')->nullable();
            $table->text('completion_criteria')->nullable();
            $table->string('status')->default('activo');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('diploma_programs', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('area_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('objective')->nullable();
            $table->text('completion_criteria')->nullable();
            $table->decimal('total_hours', 8, 2)->default(0);
            $table->string('status')->default('activo');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('learning_path_competency', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('learning_path_id')->constrained()->cascadeOnDelete();
            $table->foreignId('competency_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('order_number')->default(1);
            $table->boolean('is_required')->default(true);
            $table->timestamps();
            $table->unique(['learning_path_id', 'competency_id']);
        });

        Schema::create('competency_certification', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('competency_id')->constrained()->cascadeOnDelete();
            $table->foreignId('certification_program_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('order_number')->default(1);
            $table->boolean('is_required')->default(true);
            $table->timestamps();
            $table->unique(['competency_id', 'certification_program_id'], 'competency_certification_unique');
        });

        Schema::create('certification_diploma', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('certification_program_id')->constrained()->cascadeOnDelete();
            $table->foreignId('diploma_program_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('order_number')->default(1);
            $table->boolean('is_required')->default(true);
            $table->timestamps();
            $table->unique(['certification_program_id', 'diploma_program_id'], 'certification_diploma_unique');
        });

        Schema::create('diploma_training_program', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('diploma_program_id')->constrained()->cascadeOnDelete();
            $table->foreignId('training_program_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('order_number')->default(1);
            $table->boolean('is_required')->default(true);
            $table->decimal('minimum_score', 8, 2)->nullable();
            $table->timestamps();
            $table->unique(['diploma_program_id', 'training_program_id'], 'diploma_training_program_unique');
        });

        Schema::create('survey_training_program', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('survey_id')->constrained()->cascadeOnDelete();
            $table->foreignId('training_program_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['survey_id', 'training_program_id']);
        });

        Schema::create('diploma_program_survey', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('survey_id')->constrained()->cascadeOnDelete();
            $table->foreignId('diploma_program_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['survey_id', 'diploma_program_id']);
        });

        Schema::table('survey_responses', function (Blueprint $table): void {
            $table->foreignId('diploma_program_id')
                ->nullable()
                ->after('activity_id')
                ->constrained()
                ->nullOnDelete();
            $table->unique(
                ['survey_id', 'user_id', 'diploma_program_id'],
                'survey_user_diploma_response_unique',
            );
        });

        Schema::create('user_diploma_programs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('diploma_program_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('bloqueado');
            $table->decimal('progress_percentage', 5, 2)->default(0);
            $table->timestamp('unlocked_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'diploma_program_id']);
        });

        Schema::create('user_certification_programs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('certification_program_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('bloqueado');
            $table->decimal('progress_percentage', 5, 2)->default(0);
            $table->timestamp('unlocked_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(
                ['user_id', 'certification_program_id'],
                'user_certification_program_unique',
            );
        });

        Schema::create('user_competencies', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('competency_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('bloqueado');
            $table->decimal('progress_percentage', 5, 2)->default(0);
            $table->timestamp('unlocked_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'competency_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_competencies');
        Schema::dropIfExists('user_certification_programs');
        Schema::dropIfExists('user_diploma_programs');

        if (Schema::hasColumn('survey_responses', 'diploma_program_id')) {
            $foreignKeys = collect(Schema::getForeignKeys('survey_responses'))->pluck('name');
            $indexes = collect(Schema::getIndexes('survey_responses'))->pluck('name');

            Schema::table('survey_responses', function (Blueprint $table) use ($foreignKeys, $indexes): void {
                if ($foreignKeys->contains('survey_responses_diploma_program_id_foreign')) {
                    $table->dropForeign('survey_responses_diploma_program_id_foreign');
                }

                if ($indexes->contains('survey_user_diploma_response_unique')) {
                    $table->dropUnique('survey_user_diploma_response_unique');
                }

                $table->dropColumn('diploma_program_id');
            });
        }

        Schema::dropIfExists('diploma_program_survey');
        Schema::dropIfExists('survey_training_program');
        Schema::dropIfExists('diploma_training_program');
        Schema::dropIfExists('certification_diploma');
        Schema::dropIfExists('competency_certification');
        Schema::dropIfExists('learning_path_competency');
        Schema::dropIfExists('diploma_programs');
        Schema::dropIfExists('certification_programs');
        Schema::dropIfExists('competencies');

        if (Schema::hasColumn('learning_paths', 'deleted_at')) {
            Schema::table('learning_paths', function (Blueprint $table): void {
                $table->dropSoftDeletes();
            });
        }

        if (Schema::hasColumn('activities', 'training_program_id')) {
            $foreignKeys = collect(Schema::getForeignKeys('activities'))->pluck('name');
            $indexes = collect(Schema::getIndexes('activities'))->pluck('name');

            Schema::table('activities', function (Blueprint $table) use ($foreignKeys, $indexes): void {
                if ($foreignKeys->contains('activities_training_program_id_foreign')) {
                    $table->dropForeign('activities_training_program_id_foreign');
                }

                if ($indexes->contains('activities_program_edition_unique')) {
                    $table->dropUnique('activities_program_edition_unique');
                }

                $columns = array_values(array_filter([
                    'training_program_id',
                    'edition_number',
                    'edition_code',
                    'enrollment_start_date',
                    'enrollment_end_date',
                    'deleted_at',
                ], fn (string $column): bool => Schema::hasColumn('activities', $column)));

                if ($columns !== []) {
                    $table->dropColumn($columns);
                }
            });
        }

        Schema::dropIfExists('training_programs');
    }
};
