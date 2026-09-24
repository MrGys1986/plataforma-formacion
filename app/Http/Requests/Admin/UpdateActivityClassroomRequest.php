<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActivityClassroomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('Superadministrador') ?? false;
    }

    public function rules(): array
    {
        return [
            'classroom_url' => ['nullable', 'url:http,https', 'max:2048'],
        ];
    }
}
