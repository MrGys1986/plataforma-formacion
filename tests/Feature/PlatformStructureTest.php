<?php

namespace Tests\Feature;

use App\Models\Microcredential;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PlatformStructureTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_participant_can_open_their_dashboard(): void
    {
        $user = User::factory()->create(['status' => 'activo']);
        Role::findOrCreate('Alumno');
        $user->assignRole('Alumno');

        $this->actingAs($user)
            ->get(route('participant.dashboard'))
            ->assertOk()
            ->assertSee('Bienvenido');
    }

    public function test_an_administrator_can_open_a_filament_resource(): void
    {
        $user = User::factory()->create(['status' => 'activo']);
        Role::findOrCreate('Superadministrador');
        $user->assignRole('Superadministrador');

        $this->actingAs($user)
            ->get('/admin/administracion-sistema/users')
            ->assertOk()
            ->assertSee('Usuarios');
    }

    public function test_the_microcredential_api_returns_json(): void
    {
        $user = User::factory()->create(['status' => 'activo']);
        $microcredential = Microcredential::create([
            'user_id' => $user->id,
            'name' => 'Competencia digital',
            'status' => 'emitida',
            'issued_at' => now(),
        ]);

        $url = URL::temporarySignedRoute(
            'api.v1.microcredentials.show',
            now()->addMinutes(5),
            ['microcredential' => $microcredential],
        );

        $this->getJson($url)
            ->assertOk()
            ->assertJsonPath('data.public_id', $microcredential->public_id)
            ->assertJsonPath('data.name', 'Competencia digital')
            ->assertJsonPath('data.recipient.name', $user->name);
    }
}
