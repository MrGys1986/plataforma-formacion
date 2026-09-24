<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Audit\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request, AuditService $audit): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            $audit->log('autenticacion', 'login_fallido', newValues: [
                'email_hash' => hash('sha256', strtolower($credentials['email'])),
            ]);

            throw ValidationException::withMessages([
                'email' => 'Las credenciales proporcionadas no son válidas.',
            ]);
        }

        $request->session()->regenerate();

        if ($request->user()->status !== 'activo') {
            $audit->log('autenticacion', 'login_bloqueado', $request->user());
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'La cuenta no está activa.',
            ]);
        }

        $audit->log('autenticacion', 'login_exitoso', $request->user());

        $request->session()->forget('url.intended');

        return redirect()->to($this->homeFor($request->user()));
    }

    public function destroy(Request $request, AuditService $audit): RedirectResponse
    {
        $redirectToLogin = $request->input('redirect_to') === 'login';

        $audit->log('autenticacion', 'logout', $request->user());
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route($redirectToLogin ? 'login' : 'home');
    }

    private function homeFor(User $user): string
    {
        return match (true) {
            $user->usesFilamentDashboard() => route('filament.admin.pages.dashboard'),
            default => route('dashboard'),
        };
    }
}
