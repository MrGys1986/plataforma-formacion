<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreActivityCertificateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('Superadministrador') ?? false;
    }

    public function rules(): array
    {
        return [
            'enrollment_id' => ['required', Rule::exists('enrollments', 'id')],
            'certificate' => [
                'required',
                'file',
                'mimes:pdf',
                'mimetypes:application/pdf',
                'max:'.config('security.upload_max_kilobytes'),
            ],
        ];
    }
}
