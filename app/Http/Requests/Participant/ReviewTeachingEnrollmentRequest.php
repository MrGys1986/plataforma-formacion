<?php

namespace App\Http\Requests\Participant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReviewTeachingEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('Profesor') ?? false;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(['aprobada', 'rechazada'])],
            'reason' => ['required_if:status,rechazada', 'nullable', 'string', 'max:1000'],
        ];
    }
}
