<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $activities = Activity::query()
            ->visibleTo($request->user())
            ->with('activityType')
            ->paginate(12);

        return view('personal.courses.index', compact('activities'));
    }

    public function show(Activity $activity)
    {
        $this->authorize('view', $activity);
        $activity->load(['activityType', 'area']);

        return view('personal.courses.show', compact('activity'));
    }

    public function participants(Activity $activity)
    {
        $this->authorize('view', $activity);
        $enrollments = $activity->enrollments()->with('user')->paginate(20);

        return view('personal.courses.participants', compact('activity', 'enrollments'));
    }
}
