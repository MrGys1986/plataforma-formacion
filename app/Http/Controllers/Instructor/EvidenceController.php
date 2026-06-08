<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Activity;

class EvidenceController extends Controller
{
    public function index(Activity $activity)
    {
        $this->authorize('view', $activity);
        $evidences = $activity->evidences()->with('user')->latest()->paginate(20);

        return view('instructor.evidences.index', compact('activity', 'evidences'));
    }
}
