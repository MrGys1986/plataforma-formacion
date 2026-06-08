<?php

namespace App\Http\Requests\Evidences;

use App\Models\Evidence;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreEvidenceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', Evidence::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:3000'],
            'evidence_type' => ['required', Rule::in(['producto', 'participacion', 'evaluacion', 'otro'])],
            'activity_public_id' => ['nullable', 'ulid', Rule::exists('activities', 'public_id')],
            'enrollment_public_id' => ['nullable', 'ulid', Rule::exists('enrollments', 'public_id')],
            'file' => [
                'required',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg',
                'mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,image/png,image/jpeg',
                'max:'.config('security.upload_max_kilobytes'),
            ],
        ];
    }
}
