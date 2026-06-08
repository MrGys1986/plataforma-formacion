<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Activity;

class EvaluationController extends Controller
{
    public function index(Activity $activity)
    {
        $this->authorize('view', $activity);
        $evaluations = $activity->evaluations()->withCount('results')->paginate(20);

        return view('instructor.evaluations.index', compact('activity', 'evaluations'));
    }
}
