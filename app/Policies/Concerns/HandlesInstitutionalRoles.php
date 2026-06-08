<?php

namespace App\Policies\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

trait HandlesInstitutionalRoles
{
    public function before(User $user, string $ability): ?bool
    {
        if (in_array($ability, ['delete', 'deleteAny', 'forceDelete', 'forceDeleteAny'], true)) {
            return false;
        }

        return $user->hasRole('Superadministrador') ? true : null;
    }

    protected function isVisible(User $user, Model $model): bool
    {
        return $model->newQuery()
            ->visibleTo($user)
            ->whereKey($model->getKey())
            ->exists();
    }
}
