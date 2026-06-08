<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;
use App\Policies\Concerns\HandlesInstitutionalRoles;

class PaymentPolicy
{
    use HandlesInstitutionalRoles;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Educacion Continua', 'Externo']);
    }

    public function view(User $user, Payment $payment): bool
    {
        return $this->isVisible($user, $payment);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Educacion Continua', 'Externo']);
    }

    public function update(User $user, Payment $payment): bool
    {
        return $user->hasRole('Educacion Continua') && $payment->activity?->is_external;
    }

    public function delete(User $user, Payment $payment): bool
    {
        return false;
    }

    public function restore(User $user, Payment $payment): bool
    {
        return false;
    }

    public function forceDelete(User $user, Payment $payment): bool
    {
        return false;
    }
}
