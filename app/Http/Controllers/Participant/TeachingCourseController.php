<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeachingCourseController extends Controller
{
    public function index(Request $request): View
    {
        $activities = $request->user()
            ->instructedActivities()
            ->with(['trainingProgram', 'activityType', 'area'])
            ->withCount(['enrollments', 'evidences'])
            ->latest('start_date')
            ->paginate(12);

        return view('participant.teaching.index', compact('activities'));
    }

    public function show(Request $request, Activity $activity): View
    {
        abort_unless($activity->instructor_id === $request->user()->id, 403);

        $activity->load([
            'activityType',
            'area',
            'enrollments.user',
            'evidences.user',
        ]);

        return view('participant.teaching.show', compact('activity'));
    }
}
