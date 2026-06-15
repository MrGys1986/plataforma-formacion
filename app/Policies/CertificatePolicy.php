<?php

namespace App\Policies;

use App\Models\Certificate;
use App\Models\User;
use App\Policies\Concerns\HandlesInstitutionalRoles;

class CertificatePolicy
{
    use HandlesInstitutionalRoles;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            'Profesor', 'Alumno', 'Externo', 'Personal', 'Evaluador',
            'Recursos Humanos', 'Calidad Academica', 'Educacion Continua', 'Responsable Area',
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Certificate $certificate): bool
    {
        return $this->isVisible($user, $certificate);
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
    public function update(User $user, Certificate $certificate): bool
    {
        return ($user->hasRole('Recursos Humanos') && ! $certificate->activity?->is_external)
            || ($user->hasRole('Educacion Continua') && $certificate->activity?->is_external);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Certificate $certificate): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Certificate $certificate): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Certificate $certificate): bool
    {
        return false;
    }

    public function download(User $user, Certificate $certificate): bool
    {
        return $this->view($user, $certificate);
    }
}
