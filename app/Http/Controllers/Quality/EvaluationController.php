<?php

namespace App\Http\Controllers\Quality;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index(Request $request)
    {
        $evaluations = Evaluation::query()
            ->visibleTo($request->user())
            ->with('activity')
            ->withCount([
                'results',
                'results as approved_results_count' => fn ($query) => $query->where('result', 'aprobado'),
            ])
            ->paginate(20);

        return view('quality.evaluations.index', compact('evaluations'));
    }
}
