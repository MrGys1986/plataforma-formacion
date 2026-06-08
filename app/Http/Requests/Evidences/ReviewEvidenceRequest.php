<?php

namespace App\Http\Requests\Evidences;

use App\Models\Evidence;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ReviewEvidenceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $evidence = $this->route('evidence');

        return $evidence instanceof Evidence && Gate::allows('review', $evidence);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(['validada', 'rechazada'])],
            'reason' => ['required_if:status,rechazada', 'nullable', 'string', 'max:1000'],
        ];
    }
}
