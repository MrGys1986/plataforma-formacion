<?php

namespace App\Http\Controllers\Evaluator;

use App\Http\Controllers\Controller;
use App\Models\EvaluationResult;
use Illuminate\Http\Request;

class EvaluationResultController extends Controller
{
    public function index(Request $request)
    {
        $results = EvaluationResult::query()
            ->where('evaluator_id', $request->user()->id)
            ->with(['evaluation.activity', 'user'])
            ->latest()
            ->paginate(20);

        return view('evaluator.evaluations.index', compact('results'));
    }
}
