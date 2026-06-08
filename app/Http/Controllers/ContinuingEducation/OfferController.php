<?php

namespace App\Http\Controllers\ContinuingEducation;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        $activities = Activity::query()
            ->visibleTo($request->user())
            ->with('activityType')
            ->latest()
            ->paginate(20);

        return view('continuing-education.offers.index', compact('activities'));
    }
}
