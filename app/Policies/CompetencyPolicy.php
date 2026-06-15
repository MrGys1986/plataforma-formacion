<?php

namespace App\Policies;

use App\Models\Competency;
use App\Models\User;
use App\Policies\Concerns\HandlesInstitutionalRoles;

class CompetencyPolicy
{
    use HandlesInstitutionalRoles;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Recursos Humanos', 'Calidad Academica', 'Responsable Area']);
    }

    public function view(User $user, Competency $competency): bool
    {
        return $user->hasAnyRole(['Recursos Humanos', 'Calidad Academica'])
            || ($user->hasRole('Responsable Area') && $competency->area_id === $user->area_id);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Recursos Humanos', 'Calidad Academica', 'Responsable Area']);
    }

    public function update(User $user, Competency $competency): bool
    {
        return $this->view($user, $competency);
    }

    public function delete(User $user, Competency $competency): bool
    {
        return $user->hasRole('Superadministrador');
    }

    public function restore(User $user, Competency $competency): bool
    {
        return $user->hasRole('Superadministrador');
    }

    public function forceDelete(User $user, Competency $competency): bool
    {
        return false;
    }
}
