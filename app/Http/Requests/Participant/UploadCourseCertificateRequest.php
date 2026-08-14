<?php

namespace App\Http\Requests\Participant;

use Illuminate\Foundation\Http\FormRequest;

class UploadCourseCertificateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('Profesor') ?? false;
    }

    public function rules(): array
    {
        return [
            'certificate' => [
                'required',
                'file',
                'mimes:pdf',
                'mimetypes:application/pdf',
                'max:'.config('security.upload_max_kilobytes'),
            ],
        ];
    }

    public function attributes(): array
    {
        return ['certificate' => 'constancia'];
    }
}
