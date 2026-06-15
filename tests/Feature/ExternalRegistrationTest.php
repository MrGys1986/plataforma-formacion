<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ExternalRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['Alumno', 'Externo'] as $role) {
            Role::findOrCreate($role, 'web');
        }
    }

    public function test_login_and_external_registration_have_the_new_social_design(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('o inicia sesión con')
            ->assertSee('Microsoft')
            ->assertSee('Google');

        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Continuar con Google')
            ->assertSee('@gmail.com')
            ->assertDontSee('password_confirmation');
    }

    public function test_verified_gmail_account_can_complete_external_registration(): void
    {
        $this->mockSocialiteUser('google', $this->googleUser('google-123', 'externo@gmail.com'));

        $this->withSession(['oauth_intent' => 'external_registration'])
            ->get(route('oauth.google.callback'))
            ->assertRedirect(route('register.complete'));

        $this->get(route('register.complete'))
            ->assertOk()
            ->assertSee('externo@gmail.com');

        $response = $this->post(route('register.store'), [
            'name' => 'Participante Externo',
            'external_institution' => 'Empresa de prueba',
            'phone' => '442 123 4567',
            'user_type' => 'interno',
            'profile_type' => 'superadministrador',
            'status' => 'suspendido',
            'area_id' => 999,
        ]);

        $user = User::where('email', 'externo@gmail.com')->firstOrFail();

        $response->assertRedirect(route('participant.dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->assertSame('google-123', $user->google_id);
        $this->assertSame('externo', $user->user_type);
        $this->assertSame('externo', $user->profile_type);
        $this->assertSame('activo', $user->status);
        $this->assertNull($user->area_id);
        $this->assertTrue($user->hasRole('Externo'));
        $this->assertCount(1, $user->roles);
    }

    public function test_external_registration_rejects_non_gmail_google_accounts(): void
    {
        $this->mockSocialiteUser('google', $this->googleUser('workspace-123', 'persona@universidad.edu.mx'));

        $this->withSession(['oauth_intent' => 'external_registration'])
            ->get(route('oauth.google.callback'))
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors('social');

        $this->assertDatabaseMissing('users', ['email' => 'persona@universidad.edu.mx']);
    }

    public function test_microsoft_login_only_links_an_existing_active_user(): void
    {
        $user = User::factory()->create([
            'email' => 'persona@universidad.edu.mx',
            'status' => 'activo',
        ]);
        $user->assignRole('Alumno');

        $this->mockSocialiteUser(
            'microsoft',
            $this->socialUser('microsoft-456', 'persona@universidad.edu.mx', 'Persona Institucional'),
        );

        $this->withSession(['oauth_intent' => 'login'])
            ->get(route('oauth.microsoft.callback'))
            ->assertRedirect(route('participant.dashboard'));

        $this->assertAuthenticatedAs($user);
        $this->assertSame('microsoft-456', $user->fresh()->microsoft_id);
    }

    private function mockSocialiteUser(string $provider, SocialiteUser $user): void
    {
        $driver = Mockery::mock();
        $driver->shouldReceive('user')->once()->andReturn($user);

        Socialite::shouldReceive('driver')
            ->once()
            ->with($provider)
            ->andReturn($driver);
    }

    private function googleUser(string $id, string $email): SocialiteUser
    {
        $user = $this->socialUser($id, $email, 'Participante Google');
        $user->setRaw([
            'sub' => $id,
            'email' => $email,
            'email_verified' => true,
            'name' => 'Participante Google',
        ]);

        return $user;
    }

    private function socialUser(string $id, string $email, string $name): SocialiteUser
    {
        return (new SocialiteUser)->map([
            'id' => $id,
            'email' => $email,
            'name' => $name,
            'nickname' => null,
            'avatar' => null,
        ]);
    }
}
