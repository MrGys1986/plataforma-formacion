<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Services\LearningPaths\LearningPathProgressService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LearningController extends Controller
{
    public function show(Request $request, Enrollment $enrollment, LearningPathProgressService $progress): View
    {
        abort_unless($enrollment->user_id === $request->user()->id, 403);
        abort_unless($progress->canAccessActivity($request->user(), $enrollment->activity), 403);

        $enrollment->load([
            'activity.activityType',
            'evidences.fileUpload',
        ]);

        return view('participant.learning.show', compact('enrollment'));
    }
}
