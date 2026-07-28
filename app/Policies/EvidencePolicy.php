<?php

namespace App\Policies;

use App\Models\Evidence;
use App\Models\User;
use App\Policies\Concerns\HandlesInstitutionalRoles;

class EvidencePolicy
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
    public function view(User $user, Evidence $evidence): bool
    {
        return $this->isVisible($user, $evidence);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Profesor', 'Alumno', 'Externo', 'Personal']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Evidence $evidence): bool
    {
        return ($evidence->user_id === $user->id && $evidence->status === 'pendiente')
            || ($user->hasAnyRole(['Profesor', 'Personal']) && $evidence->activity?->instructor_id === $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Evidence $evidence): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Evidence $evidence): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Evidence $evidence): bool
    {
        return false;
    }

    public function review(User $user, Evidence $evidence): bool
    {
        return ($user->hasRole('Evaluador') && $evidence->assigned_evaluator_id === $user->id)
            || ($user->hasAnyRole(['Profesor', 'Personal']) && $evidence->activity?->instructor_id === $user->id);
    }
}
