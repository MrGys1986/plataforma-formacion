<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Participant\UpdateAvatarRequest;
use App\Services\Files\ManagedFileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfessorProfileController extends Controller
{
    public function __invoke(): View
    {
        return view('participant.professor-profile.index');
    }

    public function updateAvatar(UpdateAvatarRequest $request, ManagedFileService $files): RedirectResponse
    {
        $user = $request->user();
        $previous = $user->avatarFile;
        $avatar = $files->replace(
            $previous,
            $request->file('avatar'),
            'profile-photos/'.$user->public_id,
            $user->id,
            true,
        );

        $user->update(['avatar_file_id' => $avatar->id]);

        return back()->with('status', 'La fotografía de perfil fue actualizada.');
    }
}
