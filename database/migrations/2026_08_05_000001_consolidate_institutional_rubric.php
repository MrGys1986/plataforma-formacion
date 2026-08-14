<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $now = now();
        $institutionalName = 'Rúbrica institucional de prueba';
        $legacyNames = [
            'Rúbrica para proyecto de mejora',
            'Rúbrica para evidencia documental',
            'Rúbrica institucional general',
        ];

        $rubricId = DB::table('rubrics')->where('name', $institutionalName)->value('id');

        if ($rubricId === null) {
            $rubricId = DB::table('rubrics')->insertGetId([
                'activity_id' => null,
                'name' => $institutionalName,
                'description' => 'Rúbrica de ejemplo con criterios comunes para evaluar todas las evidencias y productos de aprendizaje.',
                'passing_score' => 70,
                'status' => 'activa',
                'created_by' => DB::table('rubrics')->whereIn('name', $legacyNames)->value('created_by'),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        } else {
            DB::table('rubrics')->where('id', $rubricId)->update([
                'activity_id' => null,
                'description' => 'Rúbrica de ejemplo con criterios comunes para evaluar todas las evidencias y productos de aprendizaje.',
                'passing_score' => 70,
                'status' => 'activa',
                'updated_at' => $now,
            ]);
        }

        DB::table('rubric_criteria')->where('rubric_id', $rubricId)->delete();

        $criteria = [
            ['Cumplimiento del objetivo', 'La evidencia responde al propósito y a las instrucciones de la actividad.', 35],
            ['Dominio del contenido', 'Aplica correctamente los conocimientos, conceptos y procedimientos requeridos.', 30],
            ['Calidad y pertinencia', 'Presenta información relevante, suficiente y sustentada.', 20],
            ['Claridad y presentación', 'La evidencia es clara, ordenada y profesional.', 15],
        ];

        foreach ($criteria as $index => [$name, $description, $weight]) {
            DB::table('rubric_criteria')->insert([
                'rubric_id' => $rubricId,
                'name' => $name,
                'description' => $description,
                'weight' => $weight,
                'max_points' => 100,
                'sort_order' => $index + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        DB::table('rubrics')->whereIn('name', $legacyNames)->delete();
    }

    public function down(): void
    {
        // La consolidación de datos institucionales no se revierte para evitar
        // volver a introducir dos criterios de evaluación contradictorios.
    }
};
