<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Surveys\StoreSurveyResponseRequest;
use App\Models\Activity;
use App\Models\Survey;
use App\Models\SurveyResponse;

class SurveyController extends Controller
{
    public function show(Survey $survey, Activity $activity)
    {
        $this->authorize('respond', [$survey, $activity]);
        $survey->load('questions');
        $response = $survey->responses()->where('user_id', auth()->id())->where('activity_id', $activity->id)->first();

        return view('participant.surveys.show', compact('survey', 'activity', 'response'));
    }

    public function store(StoreSurveyResponseRequest $request, Survey $survey, Activity $activity)
    {
        $enrollment = $activity->enrollments()->where('user_id', $request->user()->id)->firstOrFail();

        SurveyResponse::firstOrCreate([
            'survey_id' => $survey->id,
            'user_id' => $request->user()->id,
            'activity_id' => $activity->id,
        ], [
            'enrollment_id' => $enrollment->id,
            'answers' => $request->validated('answers'),
            'submitted_at' => now(),
        ]);

        return redirect()->route('participant.learning.show', $enrollment)
            ->with('status', 'Tu evaluación del curso fue registrada. Gracias por responder la encuesta.');
    }
}
