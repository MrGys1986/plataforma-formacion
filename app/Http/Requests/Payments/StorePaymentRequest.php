<?php

namespace App\Http\Requests\Payments;

use App\Models\Payment;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('create', Payment::class);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'enrollment_public_id' => ['nullable', 'ulid', Rule::exists('enrollments', 'public_id')],
            'activity_public_id' => ['required', 'ulid', Rule::exists('activities', 'public_id')],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:9999999999.99'],
            'currency' => ['required', 'string', 'size:3', Rule::in(['MXN', 'USD'])],
            'payment_method' => ['required', Rule::in(['manual', 'transferencia', 'tarjeta', 'deposito'])],
            'proof' => [
                'required',
                'file',
                'mimes:pdf,png,jpg,jpeg',
                'mimetypes:application/pdf,image/png,image/jpeg',
                'max:'.config('security.upload_max_kilobytes'),
            ],
        ];
    }
}
