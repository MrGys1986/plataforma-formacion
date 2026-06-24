<?php

namespace Tests\Feature;

use App\Filament\Pages\EditionControlPage;
use App\Filament\Resources\AreaResource;
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
            ->assertSee('Usuarios')
            ->assertSee('Recursos Humanos')
            ->assertSee('Calidad Académica');

        $this->actingAs($user)
            ->get(AreaResource::getUrl())
            ->assertOk()
            ->assertSee('Áreas');
    }

    public function test_an_administrator_can_filter_users_from_the_role_navigation(): void
    {
        $administrator = User::factory()->create([
            'name' => 'Administrador autenticado',
            'status' => 'activo',
        ]);
        Role::findOrCreate('Superadministrador');
        $administrator->assignRole('Superadministrador');

        $otherAdministrator = User::factory()->create([
            'name' => 'Otro superadministrador',
            'status' => 'activo',
        ]);
        $otherAdministrator->assignRole('Superadministrador');

        $humanResourcesUser = User::factory()->create([
            'name' => 'Usuario de recursos humanos',
            'status' => 'activo',
        ]);
        Role::findOrCreate('Recursos Humanos');
        $humanResourcesUser->assignRole('Recursos Humanos');

        $this->actingAs($administrator)
            ->get('/admin/usuarios-areas/users/recursos-humanos')
            ->assertOk()
            ->assertSee('Usuario de recursos humanos')
            ->assertDontSee('Otro superadministrador');
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

    public function test_training_program_normalizes_null_defaults_before_saving(): void
    {
        $activityType = ActivityType::create([
            'name' => 'Curso',
            'status' => 'activo',
        ]);

        $program = TrainingProgram::create([
            'activity_type_id' => $activityType->id,
            'name' => 'Curso con defaults',
            'slug' => 'curso-con-defaults',
            'default_modality' => null,
            'language' => null,
            'duration_hours' => null,
            'default_cost' => null,
            'is_external' => null,
            'requires_approval' => null,
            'requires_payment' => null,
            'requires_evaluation' => null,
            'requires_survey' => null,
            'generates_certificate' => null,
            'generates_microcredential' => null,
            'status' => null,
        ]);

        $program->refresh();

        $this->assertSame('activo', $program->status);
        $this->assertSame('presencial', $program->default_modality);
        $this->assertSame('Español', $program->language);
        $this->assertSame('0.00', $program->duration_hours);
        $this->assertSame('0.00', $program->default_cost);
        $this->assertFalse($program->is_external);
        $this->assertTrue($program->requires_approval);
        $this->assertFalse($program->requires_payment);
        $this->assertFalse($program->requires_evaluation);
        $this->assertTrue($program->requires_survey);
        $this->assertTrue($program->generates_certificate);
        $this->assertFalse($program->generates_microcredential);
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
