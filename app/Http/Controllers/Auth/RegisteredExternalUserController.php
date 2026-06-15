<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CompleteExternalRegistrationRequest;
use App\Models\User;
use App\Services\Audit\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredExternalUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function complete(): View|RedirectResponse
    {
        $pendingRegistration = session('external_google_registration');

        if (! is_array($pendingRegistration) || ($pendingRegistration['expires_at'] ?? 0) < now()->timestamp) {
            session()->forget('external_google_registration');

            return redirect()
                ->route('register')
                ->withErrors(['social' => 'La sesión de registro expiró. Inicia nuevamente con Google.']);
        }

        return view('auth.complete-registration', compact('pendingRegistration'));
    }

    public function store(
        CompleteExternalRegistrationRequest $request,
        AuditService $audit,
    ): RedirectResponse {
        $pendingRegistration = $request->session()->pull('external_google_registration');

        $user = DB::transaction(function () use ($request, $pendingRegistration): User {
            $externalRole = Role::findOrCreate('Externo', 'web');
            $user = User::create([
                'name' => $request->string('name')->trim()->toString(),
                'email' => $pendingRegistration['email'],
                'password' => Str::random(64),
                'curp' => $request->input('curp'),
                'user_type' => 'externo',
                'profile_type' => 'externo',
                'area_id' => null,
                'external_institution' => $request->string('external_institution')->trim()->toString(),
                'phone' => $request->input('phone'),
                'status' => 'activo',
                'google_id' => $pendingRegistration['google_id'],
                'email_verified_at' => now(),
            ]);

            $user->assignRole($externalRole);

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        $audit->log('autenticacion', 'registro_externo', $user, newValues: [
            'user_type' => 'externo',
            'profile_type' => 'externo',
        ]);

        return redirect()
            ->route('participant.dashboard')
            ->with('status', 'Tu cuenta externa fue creada correctamente.');
    }
}
