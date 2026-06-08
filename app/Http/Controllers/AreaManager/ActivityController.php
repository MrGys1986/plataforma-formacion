<?php

namespace App\Http\Controllers\AreaManager;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $activities = Activity::query()
            ->visibleTo($request->user())
            ->with('activityType')
            ->paginate(20);

        return view('area-manager.activities.index', compact('activities'));
    }
}
