<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('Superadministrador') ?? false;
    }

    public function rules(): array
    {
        return [
            'training_program_id' => ['required', Rule::exists('training_programs', 'id')->whereNull('deleted_at')],
            'name' => ['nullable', 'string', 'max:255'],
            'instructor_id' => ['nullable', Rule::exists('users', 'id')],
            'modality' => ['nullable', Rule::in(['presencial', 'virtual', 'hibrida'])],
            'classroom_url' => ['nullable', 'url:http,https', 'max:2048'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'enrollment_start_date' => ['nullable', 'date'],
            'enrollment_end_date' => ['nullable', 'date', 'after_or_equal:enrollment_start_date'],
            'schedule' => ['nullable', 'string', 'max:255'],
            'max_capacity' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', Rule::in(['borrador', 'publicado', 'en_inscripcion', 'cupo_lleno', 'en_curso', 'finalizado', 'cancelado', 'archivado'])],
        ];
    }
}
