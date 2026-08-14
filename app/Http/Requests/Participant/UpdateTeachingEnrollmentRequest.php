<?php

namespace App\Http\Requests\Participant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeachingEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('Profesor') ?? false;
    }

    public function rules(): array
    {
        return [
            'final_score' => ['required', 'numeric', 'min:0', 'max:100'],
            'completion_status' => ['required', Rule::in(['no_iniciado', 'en_progreso', 'completado', 'no_aprobado'])],
        ];
    }
}
