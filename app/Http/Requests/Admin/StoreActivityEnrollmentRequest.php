<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreActivityEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('Superadministrador') ?? false;
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                Rule::exists('users', 'id')->where('status', 'activo'),
                Rule::unique('enrollments')->where(
                    fn ($query) => $query->where('activity_id', $this->route('activity')->id),
                ),
            ],
            'status' => ['required', Rule::in(['solicitada', 'aprobada'])],
        ];
    }
}
