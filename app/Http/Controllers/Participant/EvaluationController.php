<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\EvaluationResult;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index(Request $request)
    {
        $results = EvaluationResult::query()
            ->where('user_id', $request->user()->id)
            ->with('evaluation.activity')
            ->latest()
            ->paginate(12);

        return view('participant.evaluations.index', compact('results'));
    }
}
