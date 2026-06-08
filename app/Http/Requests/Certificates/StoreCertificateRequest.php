<?php

namespace App\Http\Requests\Certificates;

use App\Models\Certificate;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreCertificateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', Certificate::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_public_id' => ['required', 'ulid', Rule::exists('users', 'public_id')],
            'activity_public_id' => ['nullable', 'ulid', Rule::exists('activities', 'public_id')],
            'enrollment_public_id' => ['nullable', 'ulid', Rule::exists('enrollments', 'public_id')],
            'certificate_type' => ['required', Rule::in(['participacion', 'terminacion', 'acreditacion'])],
            'status' => ['nullable', Rule::in(['pendiente', 'emitida', 'cancelada'])],
        ];
    }
}
