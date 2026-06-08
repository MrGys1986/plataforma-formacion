<?php

namespace App\Policies;

use App\Models\Activity;
use App\Models\User;
use App\Policies\Concerns\HandlesInstitutionalRoles;

class ActivityPolicy
{
    use HandlesInstitutionalRoles;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            'Profesor', 'Alumno', 'Externo', 'Instructor', 'Evaluador',
            'Recursos Humanos', 'Calidad Academica', 'Educacion Continua', 'Responsable Area',
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Activity $activity): bool
    {
        return $this->isVisible($user, $activity);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Recursos Humanos', 'Educacion Continua', 'Responsable Area']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Activity $activity): bool
    {
        return ($user->hasRole('Instructor') && $activity->instructor_id === $user->id)
            || ($user->hasRole('Responsable Area') && $activity->area_id === $user->area_id)
            || ($user->hasRole('Recursos Humanos') && ! $activity->is_external)
            || ($user->hasRole('Educacion Continua') && $activity->is_external);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Activity $activity): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Activity $activity): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Activity $activity): bool
    {
        return false;
    }

    public function publish(User $user, Activity $activity): bool
    {
        return $this->update($user, $activity);
    }
}
