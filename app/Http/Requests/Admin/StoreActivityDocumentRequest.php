<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreActivityDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('Superadministrador') ?? false;
    }

    public function rules(): array
    {
        return [
            'document_type' => ['required', Rule::in(['lista_asistencia', 'evidencia_administrativa'])],
            'title' => ['required', 'string', 'max:255'],
            'document' => [
                'required',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,csv,jpg,jpeg,png',
                'max:'.config('security.upload_max_kilobytes'),
            ],
        ];
    }
}
