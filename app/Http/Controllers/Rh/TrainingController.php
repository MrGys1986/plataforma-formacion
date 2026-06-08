<?php

namespace App\Http\Controllers\Rh;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function index(Request $request)
    {
        $activities = Activity::query()
            ->visibleTo($request->user())
            ->with('activityType')
            ->latest()
            ->paginate(20);

        return view('rh.training.index', compact('activities'));
    }
}
