<?php

namespace App\Policies;

use App\Models\Microcredential;
use App\Models\User;
use App\Policies\Concerns\HandlesInstitutionalRoles;

class MicrocredentialPolicy
{
    use HandlesInstitutionalRoles;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            'Profesor', 'Alumno', 'Externo',
            'Recursos Humanos', 'Calidad Academica', 'Educacion Continua',
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Microcredential $microcredential): bool
    {
        return $this->isVisible($user, $microcredential);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Recursos Humanos', 'Educacion Continua']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Microcredential $microcredential): bool
    {
        return ($user->hasRole('Recursos Humanos') && ! $microcredential->activity?->is_external)
            || ($user->hasRole('Educacion Continua') && $microcredential->activity?->is_external);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Microcredential $microcredential): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Microcredential $microcredential): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Microcredential $microcredential): bool
    {
        return false;
    }
}
