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
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('activities', 'slug')],
            'activity_type_public_id' => ['required', 'ulid', Rule::exists('activity_types', 'public_id')],
            'area_public_id' => ['nullable', 'ulid', Rule::exists('areas', 'public_id')],
            'instructor_public_id' => ['nullable', 'ulid', Rule::exists('users', 'public_id')],
            'description' => ['nullable', 'string', 'max:5000'],
            'modality' => ['required', Rule::in(['presencial', 'virtual', 'hibrida'])],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'duration_hours' => ['required', 'numeric', 'min:0.5', 'max:9999'],
            'cost' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'status' => ['nullable', Rule::in(['borrador', 'publicada', 'archivada', 'cancelada'])],
        ];
    }
}
