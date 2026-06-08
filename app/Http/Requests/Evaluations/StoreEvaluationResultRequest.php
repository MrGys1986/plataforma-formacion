<?php

namespace App\Http\Requests\Evaluations;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEvaluationResultRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'score' => ['nullable', 'numeric', 'between:0,100'],
            'result' => ['required', Rule::in(['aprobado', 'no_aprobado', 'pendiente'])],
            'feedback' => ['nullable', 'string', 'max:3000'],
        ];
    }
}
