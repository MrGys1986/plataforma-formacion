<?php

namespace Database\Seeders;

use App\Models\Survey;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $survey = Survey::updateOrCreate(
            ['name' => 'Encuesta general de satisfacción'],
            [
                'description' => 'Encuesta institucional para actividades de formación.',
                'is_general' => true,
                'status' => 'activa',
            ],
        );

        $questions = [
            ['¿Cómo calificarías la calidad general del curso?', 'escala'],
            ['¿El instructor explicó los temas de forma clara?', 'escala'],
            ['¿El contenido del curso fue útil para tu formación?', 'escala'],
            ['¿La organización del curso fue adecuada?', 'escala'],
            ['Comentarios adicionales', 'texto'],
        ];

        foreach ($questions as $index => [$text, $type]) {
            $survey->questions()->updateOrCreate(
                ['question_text' => $text],
                [
                    'question_type' => $type,
                    'options' => $type === 'escala' ? [1, 2, 3, 4, 5] : null,
                    'is_required' => $type !== 'texto',
                    'order_number' => $index + 1,
                ],
            );
        }
    }
}
