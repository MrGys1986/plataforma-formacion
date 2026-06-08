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
            ->with(['activityType', 'area'])
            ->latest('start_date')
            ->paginate(12);

        return view('participant.catalog.index', compact('activities'));
    }

    public function show(Activity $activity)
    {
        $this->authorize('view', $activity);
        $activity->load(['activityType', 'area', 'instructor']);

        return view('participant.catalog.show', compact('activity'));
    }
}
