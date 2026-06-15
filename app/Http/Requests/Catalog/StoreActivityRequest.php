<?php

namespace App\Http\Requests\Catalog;

use App\Models\Activity;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', Activity::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'training_program_public_id' => ['required', 'ulid', Rule::exists('training_programs', 'public_id')],
            'edition_number' => ['nullable', 'integer', 'min:1'],
            'edition_code' => ['nullable', 'string', 'max:255'],
            'area_public_id' => ['nullable', 'ulid', Rule::exists('areas', 'public_id')],
            'instructor_public_id' => ['nullable', 'ulid', Rule::exists('users', 'public_id')],
            'modality' => ['nullable', Rule::in(['presencial', 'virtual', 'hibrida'])],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'enrollment_start_date' => ['nullable', 'date'],
            'enrollment_end_date' => ['nullable', 'date', 'after_or_equal:enrollment_start_date'],
            'cost' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'status' => ['nullable', Rule::in(['borrador', 'publicado', 'en_inscripcion', 'cupo_lleno', 'en_curso', 'finalizado', 'cancelado', 'archivado'])],
        ];
    }
}
