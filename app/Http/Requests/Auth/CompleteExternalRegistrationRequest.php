<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class CompleteExternalRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $pendingRegistration = $this->session()->get('external_google_registration');

        return $this->user() === null
            && is_array($pendingRegistration)
            && ($pendingRegistration['expires_at'] ?? 0) >= now()->timestamp;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'curp' => filled($this->input('curp'))
                ? mb_strtoupper(trim((string) $this->input('curp')))
                : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'external_institution' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+\s().-]+$/'],
            'curp' => [
                'nullable',
                'string',
                'size:18',
                'regex:/^[A-Z]{4}\d{6}[HM][A-Z]{5}[A-Z0-9]\d$/',
            ],
        ];
    }
}
