<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * @var list<string>
     */
    private array $tables = [
        'users',
        'areas',
        'activity_types',
        'activities',
        'learning_paths',
        'enrollments',
        'evidences',
        'file_uploads',
        'surveys',
        'survey_questions',
        'evaluations',
        'certificates',
        'microcredentials',
        'webinars',
        'digital_resources',
        'payments',
    ];

    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            if (! Schema::hasColumn($tableName, 'public_id')) {
                Schema::table($tableName, function (Blueprint $table): void {
                    $table->ulid('public_id')->nullable()->unique();
                });
            }

            DB::table($tableName)
                ->whereNull('public_id')
                ->orderBy('id')
                ->chunkById(250, function ($records) use ($tableName): void {
                    foreach ($records as $record) {
                        DB::table($tableName)
                            ->where('id', $record->id)
                            ->update(['public_id' => (string) Str::ulid()]);
                    }
                });
        }

        if (! Schema::hasColumn('evidences', 'assigned_evaluator_id')) {
            Schema::table('evidences', function (Blueprint $table): void {
                $table->foreignId('assigned_evaluator_id')
                    ->nullable()
                    ->after('uploaded_by')
                    ->constrained('users')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('evidences', 'assigned_evaluator_id')) {
            Schema::table('evidences', function (Blueprint $table): void {
                $table->dropConstrainedForeignId('assigned_evaluator_id');
            });
        }

        foreach ($this->tables as $tableName) {
            if (Schema::hasColumn($tableName, 'public_id')) {
                Schema::table($tableName, function (Blueprint $table): void {
                    $table->dropUnique(['public_id']);
                    $table->dropColumn('public_id');
                });
            }
        }
    }
};
