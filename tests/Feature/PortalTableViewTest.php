<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PortalTableViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_quality_evidence_table_renders_with_a_collection(): void
    {
        $user = User::factory()->create(['status' => 'activo']);
        Role::findOrCreate('Calidad Academica');
        $user->assignRole('Calidad Academica');

        $this->actingAs($user)
            ->get(route('quality.evidences.index'))
            ->assertOk()
            ->assertSee('Total de evidencias')
            ->assertSee('No hay evidencias registradas.');
    }

    public function test_continuing_education_enrollment_table_renders_with_a_collection(): void
    {
        $user = User::factory()->create(['status' => 'activo']);
        Role::findOrCreate('Educacion Continua');
        $user->assignRole('Educacion Continua');

        $this->actingAs($user)
            ->get(route('continuing-education.enrollments.index'))
            ->assertOk()
            ->assertSee('Inscripciones externas')
            ->assertSee('No hay inscripciones registradas.');
    }
}
