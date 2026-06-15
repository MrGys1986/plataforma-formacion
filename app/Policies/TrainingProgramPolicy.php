<?php

namespace App\Policies;

use App\Models\TrainingProgram;
use App\Models\User;
use App\Policies\Concerns\HandlesInstitutionalRoles;

class TrainingProgramPolicy
{
    use HandlesInstitutionalRoles;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Recursos Humanos', 'Educacion Continua', 'Responsable Area']);
    }

    public function view(User $user, TrainingProgram $trainingProgram): bool
    {
        return $user->hasAnyRole(['Recursos Humanos', 'Educacion Continua'])
            || ($user->hasRole('Responsable Area') && $trainingProgram->area_id === $user->area_id);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Recursos Humanos', 'Educacion Continua', 'Responsable Area']);
    }

    public function update(User $user, TrainingProgram $trainingProgram): bool
    {
        return $this->view($user, $trainingProgram);
    }

    public function delete(User $user, TrainingProgram $trainingProgram): bool
    {
        return $user->hasRole('Superadministrador');
    }

    public function restore(User $user, TrainingProgram $trainingProgram): bool
    {
        return $user->hasRole('Superadministrador');
    }

    public function forceDelete(User $user, TrainingProgram $trainingProgram): bool
    {
        return false;
    }
}
