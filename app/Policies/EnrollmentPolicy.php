<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\User;
use App\Policies\Concerns\HandlesInstitutionalRoles;

class EnrollmentPolicy
{
    use HandlesInstitutionalRoles;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            'Profesor', 'Alumno', 'Externo', 'Instructor',
            'Recursos Humanos', 'Educacion Continua', 'Responsable Area',
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Enrollment $enrollment): bool
    {
        return $this->isVisible($user, $enrollment);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Profesor', 'Alumno', 'Externo']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Enrollment $enrollment): bool
    {
        return ($user->hasRole('Instructor') && $enrollment->activity?->instructor_id === $user->id)
            || ($user->hasRole('Responsable Area') && $enrollment->activity?->area_id === $user->area_id)
            || ($user->hasRole('Recursos Humanos') && ! $enrollment->activity?->is_external)
            || ($user->hasRole('Educacion Continua') && $enrollment->activity?->is_external);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Enrollment $enrollment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Enrollment $enrollment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Enrollment $enrollment): bool
    {
        return false;
    }

    public function approve(User $user, Enrollment $enrollment): bool
    {
        return $this->update($user, $enrollment);
    }

    public function reject(User $user, Enrollment $enrollment): bool
    {
        return $this->update($user, $enrollment);
    }
}
