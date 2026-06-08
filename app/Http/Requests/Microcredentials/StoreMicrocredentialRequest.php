<?php

namespace App\Http\Requests\Microcredentials;

use App\Models\Microcredential;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreMicrocredentialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', Microcredential::class);
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
            'description' => ['nullable', 'string', 'max:5000'],
            'user_public_id' => ['required', 'ulid', Rule::exists('users', 'public_id')],
            'activity_public_id' => ['nullable', 'ulid', Rule::exists('activities', 'public_id')],
            'certificate_public_id' => ['nullable', 'ulid', Rule::exists('certificates', 'public_id')],
            'json_payload' => ['nullable', 'array'],
            'status' => ['nullable', Rule::in(['pendiente', 'emitida', 'enviada', 'revocada'])],
        ];
    }
}
