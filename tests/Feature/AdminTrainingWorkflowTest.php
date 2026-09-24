<?php

namespace Tests\Feature;

use App\Filament\Pages\EditionControlPage;
use App\Filament\Resources\TrainingProgramResource;
use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Area;
use App\Models\Enrollment;
use App\Models\TrainingProgram;
use App\Models\User;
use App\Support\PortalConfiguration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminTrainingWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_administration_header_only_shows_top_level_navigation(): void
    {
        $portal = PortalConfiguration::forUser($this->administrator());

        $this->assertSame(
            ['Inicio', 'Usuarios', 'Formación', 'Pagos', 'Reportes'],
            collect($portal['navigation'])->pluck('label')->all(),
        );
    }

    public function test_administration_dashboard_and_legacy_sections_use_filament(): void
    {
        $administrator = $this->administrator();

        $this->actingAs($administrator)
            ->get(route('dashboard', ['section' => 'usuarios', 'q' => 'filtrado', 'status' => 'activo']))
            ->assertRedirect(route('filament.admin.pages.dashboard'));

        $this->actingAs($administrator)
            ->get(route('filament.admin.pages.dashboard'))
            ->assertOk()
            ->assertSee('fi-body-has-top-navigation', false)
            ->assertSee('Panorama de la plataforma')
            ->assertSee('Accesos rápidos')
            ->assertSee('Atención requerida')
            ->assertSee('Todo el control operativo en un solo lugar')
            ->assertSee('Plataforma Institucional de Formación y Microcredenciales')
            ->assertSee($administrator->name)
            ->assertDontSee('Un tablero central para operación')
            ->assertDontSee('fi-global-search-field', false)
            ->assertDontSee('fi-account-widget', false);
    }

    public function test_all_legacy_administration_sections_redirect_to_filament(): void
    {
        $administrator = $this->administrator();

        foreach (['usuarios', 'programas', 'actividades', 'inscripciones', 'evidencias', 'pagos', 'reportes'] as $section) {
            $this->actingAs($administrator)
                ->get(route('dashboard', ['section' => $section]))
                ->assertRedirect(route('filament.admin.pages.dashboard'));
        }
    }

    public function test_an_administrator_can_create_a_program_and_then_its_activity(): void
    {
        $administrator = $this->administrator();
        $type = ActivityType::create(['name' => 'Curso', 'status' => 'activo']);
        $area = Area::create(['name' => 'Formación', 'area_type' => 'academica', 'status' => 'activa']);

        $this->actingAs($administrator)
            ->post(route('dashboard.admin.programs.store'), [
                'name' => 'Programa de seguridad operacional',
                'activity_type_id' => $type->id,
                'area_id' => $area->id,
                'duration_hours' => 20,
                'default_modality' => 'hibrida',
                'default_cost' => 0,
                'status' => 'activo',
                'requires_approval' => 1,
                'requires_payment' => 0,
                'requires_evaluation' => 1,
                'requires_survey' => 1,
                'generates_certificate' => 1,
            ])
            ->assertRedirect();

        $program = TrainingProgram::query()->firstOrFail();
        $this->assertSame('programa-de-seguridad-operacional', $program->slug);

        $this->actingAs($administrator)
            ->get(TrainingProgramResource::getUrl())
            ->assertOk()
            ->assertSee($program->name);

        $activityResponse = $this->actingAs($administrator)
            ->post(route('dashboard.admin.activities.store'), [
                'training_program_id' => $program->id,
                'classroom_url' => 'https://classroom.google.com/c/example',
                'start_date' => '2026-09-15',
                'end_date' => '2026-10-15',
                'status' => 'borrador',
            ])
            ->assertRedirect();

        $activity = Activity::query()->firstOrFail();
        $this->assertSame($program->id, $activity->training_program_id);
        $this->assertSame($type->id, $activity->activity_type_id);
        $this->assertSame('https://classroom.google.com/c/example', $activity->classroom_url);
        $this->assertSame(1, $activity->edition_number);
        $activityResponse->assertRedirectToRoute('dashboard', [
            'section' => 'programas',
            'program' => $program->public_id,
            'activity' => $activity->public_id,
        ]);

        Role::findOrCreate('Alumno');
        $participant = User::factory()->create(['name' => 'Participante del curso', 'status' => 'activo']);
        $participant->assignRole('Alumno');

        $this->actingAs($administrator)
            ->post(route('dashboard.admin.activities.enrollments.store', $activity), [
                'user_id' => $participant->id,
                'status' => 'aprobada',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('enrollments', [
            'activity_id' => $activity->id,
            'user_id' => $participant->id,
            'status' => 'aprobada',
        ]);

        $this->actingAs($administrator)
            ->get(EditionControlPage::getUrl(['record' => $activity->getKey()]))
            ->assertOk()
            ->assertSee('Control por edición')
            ->assertSee('Inscripciones')
            ->assertSee($activity->name);
    }

    public function test_documents_and_certificates_follow_the_activity_completion_order(): void
    {
        Storage::fake('local');
        $administrator = $this->administrator();
        Role::findOrCreate('Alumno');
        $participant = User::factory()->create(['status' => 'activo']);
        $participant->assignRole('Alumno');
        $type = ActivityType::create(['name' => 'Minicurso', 'status' => 'activo']);
        $activity = Activity::create([
            'activity_type_id' => $type->id,
            'name' => 'Actividad documental',
            'slug' => 'actividad-documental',
            'duration_hours' => 4,
            'generates_certificate' => true,
            'status' => 'en_curso',
        ]);
        $enrollment = Enrollment::create([
            'user_id' => $participant->id,
            'activity_id' => $activity->id,
            'status' => 'aprobada',
            'completion_status' => 'en_progreso',
        ]);

        $this->actingAs($administrator)
            ->post(route('dashboard.admin.activities.documents.store', $activity), [
                'document_type' => 'lista_asistencia',
                'title' => 'Lista de asistencia septiembre',
                'document' => UploadedFile::fake()->create('asistencia.xlsx', 80, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('digital_resources', [
            'activity_id' => $activity->id,
            'resource_type' => 'lista_asistencia',
            'visibility' => 'interno',
        ]);

        $this->actingAs($administrator)
            ->post(route('dashboard.admin.activities.certificates.store', $activity), [
                'enrollment_id' => $enrollment->id,
                'certificate' => UploadedFile::fake()->create('constancia.pdf', 80, 'application/pdf'),
            ])
            ->assertStatus(422);

        $enrollment->update(['completion_status' => 'completado', 'completed_at' => now()]);

        $this->actingAs($administrator)
            ->post(route('dashboard.admin.activities.certificates.store', $activity), [
                'enrollment_id' => $enrollment->id,
                'certificate' => UploadedFile::fake()->create('constancia.pdf', 80, 'application/pdf'),
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('certificates', [
            'enrollment_id' => $enrollment->id,
            'issued_by' => $administrator->id,
            'status' => 'emitida',
        ]);
    }

    private function administrator(): User
    {
        Role::findOrCreate('Superadministrador');
        $administrator = User::factory()->create(['status' => 'activo']);
        $administrator->assignRole('Superadministrador');

        return $administrator;
    }
}
