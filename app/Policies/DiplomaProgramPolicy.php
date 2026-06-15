<?php

namespace App\Policies;

use App\Models\DiplomaProgram;
use App\Models\User;
use App\Policies\Concerns\HandlesInstitutionalRoles;

class DiplomaProgramPolicy
{
    use HandlesInstitutionalRoles;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Recursos Humanos', 'Calidad Academica', 'Responsable Area']);
    }

    public function view(User $user, DiplomaProgram $diplomaProgram): bool
    {
        return $user->hasAnyRole(['Recursos Humanos', 'Calidad Academica'])
            || ($user->hasRole('Responsable Area') && $diplomaProgram->area_id === $user->area_id);
    }

    public function update(User $user, DiplomaProgram $diplomaProgram): bool
    {
        return $this->view($user, $diplomaProgram);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Recursos Humanos', 'Calidad Academica', 'Responsable Area']);
    }

    public function delete(User $user, DiplomaProgram $diplomaProgram): bool
    {
        return $user->hasRole('Superadministrador');
    }

    public function restore(User $user, DiplomaProgram $diplomaProgram): bool
    {
        return $user->hasRole('Superadministrador');
    }

    public function forceDelete(User $user, DiplomaProgram $diplomaProgram): bool
    {
        return false;
    }
}
