<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['training_programs', 'diploma_programs', 'certification_programs'] as $tableName) {
            if (! Schema::hasColumn($tableName, 'competencies')) {
                Schema::table($tableName, fn (Blueprint $table) => $table->text('competencies')->nullable());
            }
        }

        // Conserva la información escrita en ediciones antes de existir el campo en programas.
        DB::table('activities')
            ->whereNotNull('training_program_id')
            ->whereNotNull('competencies')
            ->where('competencies', '!=', '')
            ->orderBy('id')
            ->get(['training_program_id', 'competencies'])
            ->each(fn (object $activity) => DB::table('training_programs')
                ->where('id', $activity->training_program_id)
                ->whereNull('competencies')
                ->update(['competencies' => $activity->competencies]));

        // Las vinculaciones antiguas se convierten en información de la ficha, sin borrar el historial.
        foreach ([
            ['learning_path_competency', 'learning_paths', 'learning_path_id'],
            ['competency_certification', 'certification_programs', 'certification_program_id'],
        ] as [$pivot, $ownerTable, $ownerKey]) {
            $links = DB::table($pivot)
                ->join('competencies', 'competencies.id', '=', $pivot.'.competency_id')
                ->orderBy($pivot.'.order_number')
                ->get([$pivot.'.'.$ownerKey.' as owner_id', 'competencies.name']);

            foreach ($links->groupBy('owner_id') as $ownerId => $definitions) {
                $text = $definitions->pluck('name')->unique()->implode("\n");
                DB::table($ownerTable)
                    ->where('id', $ownerId)
                    ->where(fn ($query) => $query->whereNull('competencies')->orWhere('competencies', ''))
                    ->update(['competencies' => $text]);
            }
        }
    }

    public function down(): void
    {
        foreach (['training_programs', 'diploma_programs', 'certification_programs'] as $tableName) {
            if (Schema::hasColumn($tableName, 'competencies')) {
                Schema::table($tableName, fn (Blueprint $table) => $table->dropColumn('competencies'));
            }
        }
    }
};
