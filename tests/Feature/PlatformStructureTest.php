<?php

namespace Tests\Feature;

use App\Filament\Pages\EditionControlPage;
use App\Filament\Resources\TrainingProgramResource;
use App\Filament\Resources\UserResource;
use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Microcredential;
use App\Models\TrainingProgram;
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
            ->get(UserResource::getUrl())
            ->assertOk()
            ->assertSee('Usuarios');
    }

    public function test_an_administrator_can_open_configured_training_views_and_edition_control(): void
    {
        $user = User::factory()->create(['status' => 'activo']);
        Role::findOrCreate('Superadministrador');
        $user->assignRole('Superadministrador');

        $activityType = ActivityType::create([
            'name' => 'Curso',
            'status' => 'activo',
        ]);

        $program = TrainingProgram::create([
            'activity_type_id' => $activityType->id,
            'name' => 'Curso de prueba',
            'slug' => 'curso-de-prueba',
            'duration_hours' => 10,
            'status' => 'activo',
        ]);

        $edition = Activity::create([
            'training_program_id' => $program->id,
            'activity_type_id' => $activityType->id,
            'name' => 'Curso de prueba - Edición 1',
            'slug' => 'curso-de-prueba-edicion-1',
            'edition_number' => 1,
            'status' => 'publicado',
            'duration_hours' => 10,
        ]);

        $this->actingAs($user)
            ->get(TrainingProgramResource::getUrl(configuration: 'cursos'))
            ->assertOk()
            ->assertSee('Cursos');

        $this->actingAs($user)
            ->get(EditionControlPage::getUrl(['record' => $edition->id]))
            ->assertOk()
            ->assertSee('Control por edición');
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
