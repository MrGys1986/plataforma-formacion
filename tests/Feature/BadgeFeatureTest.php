<?php

namespace Tests\Feature;

use App\Models\Microcredential;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class BadgeFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_professor_can_view_their_badge_and_its_public_verification(): void
    {
        Role::findOrCreate('Profesor');
        $professor = User::factory()->create(['status' => 'activo']);
        $professor->assignRole('Profesor');

        $badge = Microcredential::create([
            'user_id' => $professor->id,
            'name' => 'Docencia innovadora',
            'description' => 'Insignia de prueba.',
            'status' => 'validada',
            'issued_at' => now(),
        ]);

        $this->actingAs($professor)
            ->get(route('participant.badges.index'))
            ->assertOk()
            ->assertSee('Mis insignias')
            ->assertSee('Docencia innovadora');

        $this->actingAs($professor)
            ->get(route('participant.badges.show', $badge))
            ->assertOk()
            ->assertSee('Verificada')
            ->assertSee($badge->public_id);

        $this->get(route('public.badges.verify', $badge))
            ->assertOk()
            ->assertSee('Insignia válida y verificada')
            ->assertSee($professor->name);
    }
}
