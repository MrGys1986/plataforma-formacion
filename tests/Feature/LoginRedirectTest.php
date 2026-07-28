<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LoginRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_professor_is_sent_directly_to_their_dashboard_after_login(): void
    {
        Role::findOrCreate('Profesor');

        $professor = User::factory()->create([
            'email' => 'profesor@prueba.mx',
            'password' => Hash::make('12345678'),
            'status' => 'activo',
        ]);
        $professor->assignRole('Profesor');

        $this->withSession(['url.intended' => route('home')])
            ->post(route('login.store'), [
                'email' => 'profesor@prueba.mx',
                'password' => '12345678',
            ])
            ->assertRedirect(route('participant.dashboard'));

        $this->assertAuthenticatedAs($professor);
        $this->assertNull(session('url.intended'));
    }
}
