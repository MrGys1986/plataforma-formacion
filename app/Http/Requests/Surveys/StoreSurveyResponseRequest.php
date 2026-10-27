<?php

namespace App\Http\Requests\Surveys;

use App\Models\Activity;
use App\Models\Survey;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

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
        $survey = $this->route('survey');
        $questions = $survey->questions->keyBy('public_id');
        $rules = [
            'answers' => ['required', 'array'],
            'answers.*' => ['array:question_public_id,value'],
            'answers.*.question_public_id' => ['required', 'ulid', 'distinct', Rule::exists('survey_questions', 'public_id')->where('survey_id', $survey->id)],
            'answers.*.value' => ['nullable', 'string', 'max:2000'],
        ];

        foreach ((array) $this->input('answers', []) as $index => $answer) {
            $question = $questions->get(is_array($answer) ? ($answer['question_public_id'] ?? '') : '');

            if (! $question) {
                continue;
            }

            $rules["answers.$index.value"] = [$question->is_required ? 'required' : 'nullable', 'string', 'max:2000'];

            if (in_array($question->question_type, ['escala', 'opcion_multiple'], true)) {
                $rules["answers.$index.value"][] = Rule::in(array_keys($question->answerOptions()));
            }
        }

        return $rules;
    }

    public function after(): array
    {
        return [function (Validator $validator): void {
            $submitted = collect((array) $this->input('answers', []))->pluck('question_public_id');

            foreach ($this->route('survey')->questions->where('is_required', true) as $question) {
                if (! $submitted->contains($question->public_id)) {
                    $validator->errors()->add('answers', 'Responde todas las preguntas obligatorias.');
                }
            }
        }];
    }
}
