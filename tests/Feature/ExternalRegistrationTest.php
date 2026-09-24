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

    public function test_login_offers_credentials_and_microsoft_but_not_google(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Correo electrónico')
            ->assertSee('Contraseña')
            ->assertSee('o inicia sesión con')
            ->assertSee('Microsoft')
            ->assertDontSee('Google')
            ->assertDontSee('Registrarme como externo');
    }

    public function test_google_authentication_and_registration_routes_are_disabled(): void
    {
        $this->get('/auth/google/redirect')->assertNotFound();
        $this->get('/auth/google/callback')->assertNotFound();
        $this->get('/register')->assertNotFound();
        $this->get('/register/google')->assertNotFound();
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
            ->assertRedirect(route('dashboard'));

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
