<?php

namespace App\Observers;

use App\Models\Payment;

class PaymentEnrollmentObserver
{
    public function updating(Payment $payment): void
    {
        if (! $payment->isDirty('status')) {
            return;
        }

        if (in_array($payment->status, ['validado', 'pagado'], true)) {
            $payment->validated_by ??= auth()->id();
            $payment->validated_at ??= now();
        }

        if ($payment->status === 'rechazado') {
            $payment->validated_by = null;
            $payment->validated_at = null;
        }
    }

    public function updated(Payment $payment): void
    {
        if (! $payment->wasChanged('status') || ! $payment->enrollment_id) {
            return;
        }

        $enrollment = $payment->enrollment;

        if (! $enrollment) {
            return;
        }

        if (in_array($payment->status, ['validado', 'pagado'], true)) {
            $enrollment->update([
                'payment_status' => 'validado',
                'requested_at' => $enrollment->requested_at ?? now(),
            ]);

            return;
        }

        if ($payment->status === 'rechazado') {
            $enrollment->update(['payment_status' => 'rechazado']);
        }
    }
}
