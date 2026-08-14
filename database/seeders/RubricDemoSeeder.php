<?php

namespace Database\Seeders;

use App\Models\Rubric;
use App\Models\User;
use Illuminate\Database\Seeder;

class RubricDemoSeeder extends Seeder
{
    public function run(): void
    {
        $evaluator = User::query()->role('Evaluador')->first();
        $criteria = [
            ['name' => 'Cumplimiento del objetivo', 'description' => 'La evidencia responde al propósito y a las instrucciones de la actividad.', 'weight' => 35],
            ['name' => 'Dominio del contenido', 'description' => 'Aplica correctamente los conocimientos, conceptos y procedimientos requeridos.', 'weight' => 30],
            ['name' => 'Calidad y pertinencia', 'description' => 'Presenta información relevante, suficiente y sustentada.', 'weight' => 20],
            ['name' => 'Claridad y presentación', 'description' => 'La evidencia es clara, ordenada y profesional.', 'weight' => 15],
        ];

        Rubric::query()->whereIn('name', [
            'Rúbrica para proyecto de mejora',
            'Rúbrica para evidencia documental',
            'Rúbrica institucional general',
        ])->delete();

        $rubric = Rubric::query()->updateOrCreate(
            ['name' => 'Rúbrica institucional de prueba'],
            [
                'description' => 'Rúbrica de ejemplo con criterios comunes para evaluar todas las evidencias y productos de aprendizaje.',
                'passing_score' => 70,
                'activity_id' => null,
                'created_by' => $evaluator?->id,
                'status' => 'activa',
            ],
        );

        $rubric->criteria()->whereNotIn('name', collect($criteria)->pluck('name'))->delete();

        foreach ($criteria as $index => $criterion) {
            $rubric->criteria()->updateOrCreate(
                ['name' => $criterion['name']],
                $criterion + ['max_points' => 100, 'sort_order' => $index + 1],
            );
        }
    }
}
