<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $activities = Activity::query()
            ->visibleTo($request->user())
            ->whereIn('status', ['publicado', 'en_inscripcion'])
            ->with(['activityType', 'area'])
            ->latest('start_date')
            ->paginate(12);

        return view('participant.catalog.index', compact('activities'));
    }

    public function show(Request $request, Activity $activity)
    {
        $this->authorize('view', $activity);
        $activity->load(['activityType', 'area', 'instructor']);
        $enrollment = $request->user()
            ->enrollments()
            ->where('activity_id', $activity->id)
            ->first();

        return view('participant.catalog.show', compact('activity', 'enrollment'));
    }
}
