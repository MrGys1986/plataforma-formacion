<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\HandlesInstitutionalRoles;

class UserPolicy
{
    use HandlesInstitutionalRoles;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Recursos Humanos', 'Educacion Continua', 'Responsable Area']);
    }

    public function view(User $user, User $model): bool
    {
        return $this->isVisible($user, $model);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Recursos Humanos', 'Educacion Continua']);
    }

    public function update(User $user, User $model): bool
    {
        return $user->id === $model->id
            || ($user->hasRole('Recursos Humanos') && $model->user_type !== 'externo')
            || ($user->hasRole('Educacion Continua') && $model->user_type === 'externo');
    }

    public function delete(User $user, User $model): bool
    {
        return false;
    }

    public function restore(User $user, User $model): bool
    {
        return false;
    }

    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }

    public function changeRoles(User $user, User $model): bool
    {
        return $user->hasRole('Superadministrador') && $user->id !== $model->id;
    }
}
