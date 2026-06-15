<?php

namespace App\Policies;

use App\Models\CertificationProgram;
use App\Models\User;
use App\Policies\Concerns\HandlesInstitutionalRoles;

class CertificationProgramPolicy
{
    use HandlesInstitutionalRoles;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Recursos Humanos', 'Calidad Academica', 'Responsable Area']);
    }

    public function view(User $user, CertificationProgram $certificationProgram): bool
    {
        return $user->hasAnyRole(['Recursos Humanos', 'Calidad Academica'])
            || ($user->hasRole('Responsable Area') && $certificationProgram->area_id === $user->area_id);
    }

    public function update(User $user, CertificationProgram $certificationProgram): bool
    {
        return $this->view($user, $certificationProgram);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Recursos Humanos', 'Calidad Academica', 'Responsable Area']);
    }

    public function delete(User $user, CertificationProgram $certificationProgram): bool
    {
        return $user->hasRole('Superadministrador');
    }

    public function restore(User $user, CertificationProgram $certificationProgram): bool
    {
        return $user->hasRole('Superadministrador');
    }

    public function forceDelete(User $user, CertificationProgram $certificationProgram): bool
    {
        return false;
    }
}
