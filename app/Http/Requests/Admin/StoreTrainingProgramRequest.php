<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTrainingProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('Superadministrador') ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'activity_type_id' => ['required', Rule::exists('activity_types', 'id')],
            'area_id' => ['nullable', Rule::exists('areas', 'id')],
            'description' => ['nullable', 'string', 'max:5000'],
            'duration_hours' => ['required', 'numeric', 'min:0', 'max:99999'],
            'default_modality' => ['required', Rule::in(['presencial', 'virtual', 'hibrida'])],
            'default_cost' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['activo', 'inactivo', 'borrador'])],
            'requires_approval' => ['nullable', 'boolean'],
            'requires_payment' => ['nullable', 'boolean'],
            'requires_evaluation' => ['nullable', 'boolean'],
            'requires_survey' => ['nullable', 'boolean'],
            'generates_certificate' => ['nullable', 'boolean'],
        ];
    }
}
