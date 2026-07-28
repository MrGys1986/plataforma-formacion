<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LearningController extends Controller
{
    public function show(Request $request, Enrollment $enrollment): View
    {
        abort_unless($enrollment->user_id === $request->user()->id, 403);

        $enrollment->load([
            'activity.activityType',
            'evidences.fileUpload',
        ]);

        return view('participant.learning.show', compact('enrollment'));
    }
}
