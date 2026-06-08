<?php

namespace App\Http\Requests\Surveys;

use App\Models\Activity;
use App\Models\Survey;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreSurveyResponseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $survey = $this->route('survey');
        $activity = $this->route('activity');

        return $survey instanceof Survey
            && $activity instanceof Activity
            && Gate::allows('respond', [$survey, $activity]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'answers' => ['required', 'array'],
            'answers.*.question_public_id' => ['required', 'ulid', 'exists:survey_questions,public_id'],
            'answers.*.value' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
