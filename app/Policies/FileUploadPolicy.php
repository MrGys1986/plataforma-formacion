<?php

namespace App\Policies;

use App\Models\FileUpload;
use App\Models\User;
use App\Policies\Concerns\HandlesInstitutionalRoles;

class FileUploadPolicy
{
    use HandlesInstitutionalRoles;

    public function viewAny(User $user): bool
    {
        return $user->roles()->exists();
    }

    public function view(User $user, FileUpload $fileUpload): bool
    {
        return $this->isVisible($user, $fileUpload);
    }

    public function download(User $user, FileUpload $fileUpload): bool
    {
        return $this->view($user, $fileUpload);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, FileUpload $fileUpload): bool
    {
        return false;
    }

    public function delete(User $user, FileUpload $fileUpload): bool
    {
        return false;
    }

    public function restore(User $user, FileUpload $fileUpload): bool
    {
        return false;
    }

    public function forceDelete(User $user, FileUpload $fileUpload): bool
    {
        return false;
    }
}
