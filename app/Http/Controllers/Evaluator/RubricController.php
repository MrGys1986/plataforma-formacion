<?php
namespace App\Http\Controllers\Evaluator;
use App\Http\Controllers\Controller;
use App\Models\Rubric;

class RubricController extends Controller
{
    public function index()
    {
        $this->ensureInstitutionalTestRubricExists();

        $rubrics = Rubric::query()->where('status', 'activa')
            ->whereNull('activity_id')
            ->with(['activity', 'criteria', 'createdBy'])->withCount('criteria')
            ->orderBy('name')->paginate(12);
        return view('evaluator.rubrics.index', compact('rubrics'));
    }

    private function ensureInstitutionalTestRubricExists(): void
    {
        $rubric = Rubric::query()->firstOrCreate(
            ['name' => 'Rúbrica institucional de prueba'],
            [
                'activity_id' => null,
                'description' => 'Rúbrica de ejemplo con criterios comunes para evaluar todas las evidencias y productos de aprendizaje.',
                'passing_score' => 70,
                'status' => 'activa',
                'created_by' => auth()->id(),
            ],
        );

        if ($rubric->activity_id !== null || $rubric->status !== 'activa') {
            $rubric->update(['activity_id' => null, 'status' => 'activa']);
        }

        $criteria = [
            ['name' => 'Cumplimiento del objetivo', 'description' => 'La evidencia responde al propósito y a las instrucciones de la actividad.', 'weight' => 35],
            ['name' => 'Dominio del contenido', 'description' => 'Aplica correctamente los conocimientos, conceptos y procedimientos requeridos.', 'weight' => 30],
            ['name' => 'Calidad y pertinencia', 'description' => 'Presenta información relevante, suficiente y sustentada.', 'weight' => 20],
            ['name' => 'Claridad y presentación', 'description' => 'La evidencia es clara, ordenada y profesional.', 'weight' => 15],
        ];

        foreach ($criteria as $index => $criterion) {
            $rubric->criteria()->firstOrCreate(
                ['name' => $criterion['name']],
                $criterion + ['max_points' => 100, 'sort_order' => $index + 1],
            );
        }
    }
}
