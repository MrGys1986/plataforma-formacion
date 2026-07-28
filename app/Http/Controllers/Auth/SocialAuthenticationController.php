<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Audit\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialUser;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;
use Throwable;

class SocialAuthenticationController extends Controller
{
    public function redirectGoogle(Request $request): SymfonyRedirectResponse|RedirectResponse
    {
        return $this->redirectToProvider($request, 'google', 'login');
    }

    public function redirectMicrosoft(Request $request): SymfonyRedirectResponse|RedirectResponse
    {
        return $this->redirectToProvider($request, 'microsoft', 'login');
    }

    public function redirectExternalRegistration(Request $request): SymfonyRedirectResponse|RedirectResponse
    {
        return $this->redirectToProvider($request, 'google', 'external_registration');
    }

    public function callbackGoogle(Request $request, AuditService $audit): RedirectResponse
    {
        return $this->handleCallback($request, $audit, 'google');
    }

    public function callbackMicrosoft(Request $request, AuditService $audit): RedirectResponse
    {
        return $this->handleCallback($request, $audit, 'microsoft');
    }

    private function redirectToProvider(
        Request $request,
        string $provider,
        string $intent,
    ): SymfonyRedirectResponse|RedirectResponse {
        if (! $this->providerIsConfigured($provider)) {
            return back()->withErrors([
                'social' => "El acceso con {$this->providerLabel($provider)} aún no está configurado.",
            ]);
        }

        $request->session()->put('oauth_intent', $intent);
        $driver = Socialite::driver($provider);

        if ($provider === 'google') {
            $driver->with(['prompt' => 'select_account']);
        }

        return $driver->redirect();
    }

    private function handleCallback(
        Request $request,
        AuditService $audit,
        string $provider,
    ): RedirectResponse {
        $intent = $request->session()->pull('oauth_intent', 'login');

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (Throwable $exception) {
            report($exception);

            return $this->socialFailure(
                $intent,
                'No fue posible validar la cuenta con el proveedor seleccionado. Inténtalo nuevamente.',
            );
        }

        if ($intent === 'external_registration') {
            return $this->prepareExternalRegistration($request, $audit, $provider, $socialUser);
        }

        return $this->loginExistingUser($request, $audit, $provider, $socialUser);
    }

    private function prepareExternalRegistration(
        Request $request,
        AuditService $audit,
        string $provider,
        SocialUser $socialUser,
    ): RedirectResponse {
        $email = Str::lower(trim((string) $socialUser->getEmail()));

        if ($provider !== 'google' || ! $this->googleEmailIsVerified($socialUser)) {
            return $this->socialFailure(
                'external_registration',
                'El registro externo requiere una cuenta de Google con correo verificado.',
            );
        }

        if (! Str::endsWith($email, '@gmail.com')) {
            return $this->socialFailure(
                'external_registration',
                'El registro externo requiere una cuenta personal terminada en @gmail.com. Las cuentas institucionales deben ser dadas de alta por la institución.',
            );
        }

        $existingUser = User::query()
            ->where('google_id', (string) $socialUser->getId())
            ->orWhere('email', $email)
            ->first();

        if ($existingUser) {
            if ($existingUser->user_type !== 'externo' || ! $existingUser->hasRole('Externo')) {
                return $this->socialFailure(
                    'external_registration',
                    'Este correo ya pertenece a una cuenta institucional y no puede registrarse como participante externo.',
                );
            }

            if (filled($existingUser->google_id) && $existingUser->google_id !== (string) $socialUser->getId()) {
                return $this->socialFailure(
                    'external_registration',
                    'La cuenta de Google no coincide con la identidad vinculada previamente.',
                );
            }

            if ($existingUser->status !== 'activo') {
                return $this->socialFailure(
                    'external_registration',
                    'La cuenta externa existe, pero no está activa.',
                );
            }

            $existingUser->forceFill([
                'google_id' => (string) $socialUser->getId(),
                'email_verified_at' => $existingUser->email_verified_at ?? now(),
            ])->save();

            Auth::login($existingUser, true);
            $request->session()->regenerate();
            $audit->log('autenticacion', 'login_google_externo', $existingUser);

            return redirect()->route('participant.dashboard');
        }

        $request->session()->put('external_google_registration', [
            'google_id' => (string) $socialUser->getId(),
            'email' => $email,
            'name' => trim((string) $socialUser->getName()),
            'expires_at' => now()->addMinutes(15)->timestamp,
        ]);

        return redirect()->route('register.complete');
    }

    private function loginExistingUser(
        Request $request,
        AuditService $audit,
        string $provider,
        SocialUser $socialUser,
    ): RedirectResponse {
        $providerId = (string) $socialUser->getId();
        $providerColumn = "{$provider}_id";
        $email = Str::lower(trim((string) $socialUser->getEmail()));

        if (blank($providerId) || blank($email)) {
            return $this->socialFailure('login', 'El proveedor no devolvió una identidad o correo válidos.');
        }

        if ($provider === 'google' && ! $this->googleEmailIsVerified($socialUser)) {
            return $this->socialFailure('login', 'La cuenta de Google no tiene un correo verificado.');
        }

        $user = User::query()
            ->where($providerColumn, $providerId)
            ->orWhere('email', $email)
            ->first();

        if (! $user) {
            return $this->socialFailure(
                'login',
                'No existe una cuenta registrada con ese correo. Si eres participante externo, utiliza el registro con Google.',
            );
        }

        if (filled($user->{$providerColumn}) && $user->{$providerColumn} !== $providerId) {
            return $this->socialFailure('login', 'La identidad social no coincide con la cuenta vinculada.');
        }

        if ($user->status !== 'activo') {
            return $this->socialFailure('login', 'La cuenta existe, pero no está activa.');
        }

        $user->forceFill([
            $providerColumn => $providerId,
            'email_verified_at' => $user->email_verified_at ?? now(),
        ])->save();

        Auth::login($user, true);
        $request->session()->regenerate();
        $audit->log('autenticacion', "login_{$provider}", $user);

        request()->session()->forget('url.intended');

        return redirect()->to($this->homeFor($user));
    }

    private function googleEmailIsVerified(SocialUser $socialUser): bool
    {
        return (bool) data_get(
            $socialUser->user,
            'email_verified',
            data_get($socialUser->user, 'verified_email', false),
        );
    }

    private function socialFailure(string $intent, string $message): RedirectResponse
    {
        return redirect()
            ->route($intent === 'external_registration' ? 'register' : 'login')
            ->withErrors(['social' => $message]);
    }

    private function providerIsConfigured(string $provider): bool
    {
        return filled(config("services.{$provider}.client_id"))
            && filled(config("services.{$provider}.client_secret"))
            && filled(config("services.{$provider}.redirect"));
    }

    private function providerLabel(string $provider): string
    {
        return $provider === 'microsoft' ? 'Microsoft' : 'Google';
    }

    private function homeFor(User $user): string
    {
        return match (true) {
            $user->hasRole('Personal') => route('personal.dashboard'),
            $user->hasRole('Evaluador') => route('evaluator.dashboard'),
            $user->hasRole('Recursos Humanos') => route('rh.dashboard'),
            $user->hasRole('Calidad Academica') => route('quality.dashboard'),
            $user->hasRole('Educacion Continua') => route('continuing-education.dashboard'),
            $user->hasRole('Responsable Area') => route('area-manager.dashboard'),
            $user->hasAnyRole(['Profesor', 'Alumno', 'Externo']) => route('participant.dashboard'),
            default => route('filament.admin.pages.dashboard'),
        };
    }
}
