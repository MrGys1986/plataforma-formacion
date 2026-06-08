<?php

namespace App\Http\Requests\Catalog;

use App\Models\Activity;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $activity = $this->route('activity');

        return $activity instanceof Activity && Gate::allows('update', $activity);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => [
                'sometimes', 'required', 'string', 'max:255', 'alpha_dash',
                Rule::unique('activities', 'slug')->ignore($this->route('activity')),
            ],
            'activity_type_public_id' => ['sometimes', 'required', 'ulid', Rule::exists('activity_types', 'public_id')],
            'area_public_id' => ['nullable', 'ulid', Rule::exists('areas', 'public_id')],
            'instructor_public_id' => ['nullable', 'ulid', Rule::exists('users', 'public_id')],
            'description' => ['nullable', 'string', 'max:5000'],
            'modality' => ['sometimes', Rule::in(['presencial', 'virtual', 'hibrida'])],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'duration_hours' => ['sometimes', 'numeric', 'min:0.5', 'max:9999'],
            'cost' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'status' => ['nullable', Rule::in(['borrador', 'publicada', 'archivada', 'cancelada'])],
        ];
    }
}
