<?php

namespace App\Policies;

use App\Models\Activity;
use App\Models\Survey;
use App\Models\User;
use App\Policies\Concerns\HandlesInstitutionalRoles;

class SurveyPolicy
{
    use HandlesInstitutionalRoles;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            'Profesor', 'Alumno', 'Externo', 'Recursos Humanos',
            'Calidad Academica', 'Educacion Continua',
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Survey $survey): bool
    {
        return $this->isVisible($user, $survey);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Survey $survey): bool
    {
        return $user->hasAnyRole(['Recursos Humanos', 'Calidad Academica']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Survey $survey): bool
    {
        return $user->hasAnyRole(['Recursos Humanos', 'Calidad Academica']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Survey $survey): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Survey $survey): bool
    {
        return false;
    }

    public function respond(User $user, Survey $survey, Activity $activity): bool
    {
        return $survey->status === 'activa'
            && $activity->enrollments()
                ->where('user_id', $user->id)
                ->whereNotIn('status', ['rechazada', 'cancelada'])
                ->exists();
    }
}
