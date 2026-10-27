<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\EvaluationResult;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index(Request $request)
    {
        $gradedEnrollments = $request->user()->enrollments()
            ->where('status', 'aprobada')->whereNotNull('final_score')
            ->with('activity.activityType')->latest('updated_at')->paginate(12, ['*'], 'grades_page');
        $results = EvaluationResult::query()
            ->where('user_id', $request->user()->id)
            ->with('evaluation.activity')
            ->latest()
            ->paginate(12);

        return view('participant.evaluations.index', compact('results', 'gradedEnrollments'));
    }
}
