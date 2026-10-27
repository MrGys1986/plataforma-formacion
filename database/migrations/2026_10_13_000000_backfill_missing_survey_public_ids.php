<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['surveys', 'survey_questions'] as $table) {
            DB::table($table)
                ->where(fn ($query) => $query->whereNull('public_id')->orWhere('public_id', ''))
                ->orderBy('id')
                ->chunkById(250, function ($records) use ($table): void {
                    foreach ($records as $record) {
                        DB::table($table)->where('id', $record->id)
                            ->where(fn ($query) => $query->whereNull('public_id')->orWhere('public_id', ''))
                            ->update(['public_id' => (string) Str::ulid()]);
                    }
                });
        }
    }

    public function down(): void
    {
        // Retain repaired identifiers so existing links and answers remain valid.
    }
};
