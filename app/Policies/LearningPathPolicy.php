<?php

namespace App\Policies;

use App\Models\LearningPath;
use App\Models\User;
use App\Policies\Concerns\HandlesInstitutionalRoles;

class LearningPathPolicy
{
    use HandlesInstitutionalRoles;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            'Profesor', 'Alumno', 'Externo', 'Recursos Humanos',
            'Calidad Academica', 'Responsable Area',
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LearningPath $learningPath): bool
    {
        return $this->isVisible($user, $learningPath);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Recursos Humanos', 'Responsable Area']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LearningPath $learningPath): bool
    {
        return ($user->hasRole('Responsable Area') && $learningPath->area_id === $user->area_id)
            || $user->hasRole('Recursos Humanos');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LearningPath $learningPath): bool
    {
        return $user->hasRole('Superadministrador');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LearningPath $learningPath): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LearningPath $learningPath): bool
    {
        return false;
    }
}
