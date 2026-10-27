<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Survey;
use App\Models\SurveyResponse;
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
            'certificates' => fn ($query) => $query->where('status', 'emitida')->with('fileUpload'),
            'evidences.fileUpload',
        ]);
        $surveys = Survey::query()->forActivity($enrollment->activity)->whereHas('questions')->get();
        $answeredSurveyIds = SurveyResponse::query()->where('user_id', $request->user()->id)
            ->where('activity_id', $enrollment->activity_id)->pluck('survey_id');

        return view('participant.learning.show', compact('enrollment', 'surveys', 'answeredSurveyIds'));
    }
}
