<?php

namespace App\Policies;

use App\Models\Evaluation;
use App\Models\User;
use App\Policies\Concerns\HandlesInstitutionalRoles;

class EvaluationPolicy
{
    use HandlesInstitutionalRoles;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            'Personal', 'Evaluador', 'Recursos Humanos', 'Calidad Academica',
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Evaluation $evaluation): bool
    {
        return $this->isVisible($user, $evaluation);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Personal', 'Recursos Humanos']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Evaluation $evaluation): bool
    {
        return ($user->hasRole('Personal') && $evaluation->activity?->instructor_id === $user->id)
            || ($user->hasRole('Recursos Humanos') && ! $evaluation->activity?->is_external);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Evaluation $evaluation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Evaluation $evaluation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Evaluation $evaluation): bool
    {
        return false;
    }
}
