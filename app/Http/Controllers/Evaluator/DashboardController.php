<?php

namespace App\Http\Controllers\Evaluator;

use App\Http\Controllers\Controller;
use App\Models\EvaluationResult;
use App\Models\Evidence;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $evidences = Evidence::query()->visibleTo($request->user());
        $results = EvaluationResult::query()->where('evaluator_id', $request->user()->id);

        return view('evaluator.dashboard', [
            'stats' => [
                ['label' => 'Evidencias asignadas', 'value' => (clone $evidences)->count(), 'description' => 'Expedientes bajo tu revisión'],
                ['label' => 'Pendientes', 'value' => (clone $evidences)->where('status', 'pendiente')->count(), 'description' => 'Requieren dictamen'],
                ['label' => 'Revisadas', 'value' => (clone $evidences)->whereIn('status', ['aprobada', 'rechazada'])->count(), 'description' => 'Evidencias dictaminadas'],
                ['label' => 'Evaluaciones', 'value' => (clone $results)->count(), 'description' => 'Resultados registrados'],
            ],
        ]);
    }
}
