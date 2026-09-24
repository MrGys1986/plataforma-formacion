<?php

namespace Tests\Feature;

use App\Filament\Pages\EditionControlPage;
use App\Filament\Pages\ProgramControlPage;
use App\Filament\Resources\ActivityResource;
use App\Filament\Resources\AreaResource;
use App\Filament\Resources\AreaResource\Pages\ManageAreas;
use App\Filament\Resources\EnrollmentResource;
use App\Filament\Resources\SurveyQuestionResource;
use App\Filament\Resources\SurveyQuestionResource\Pages\ManageSurveyQuestions;
use App\Filament\Resources\SurveyResponseResource;
use App\Filament\Resources\TrainingProgramResource;
use App\Filament\Resources\TrainingProgramResource\Pages\ManageTrainingPrograms;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Pages\ManageUsers;
use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Enrollment;
use App\Models\Microcredential;
use App\Models\Survey;
use App\Models\TrainingProgram;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;
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
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Portal del alumno')
            ->assertSee('Bienvenido')
            ->assertSee('Cursos inscritos')
            ->assertSee('En progreso')
            ->assertSee('Completados')
            ->assertSee('Constancias');
    }

    public function test_a_professor_has_separate_learning_and_teaching_contexts(): void
    {
        Role::findOrCreate('Profesor');

        $professor = User::factory()->create(['status' => 'activo']);
        $professor->assignRole('Profesor');

        $otherProfessor = User::factory()->create(['status' => 'activo']);
        $otherProfessor->assignRole('Profesor');

        $activityType = ActivityType::create([
            'name' => 'Curso',
            'status' => 'activo',
        ]);

        $program = TrainingProgram::create([
            'activity_type_id' => $activityType->id,
            'name' => 'Programa activo para profesores',
            'slug' => 'programa-activo-para-profesores',
            'duration_hours' => 12,
            'status' => 'activo',
        ]);

        $assignedEdition = Activity::create([
            'training_program_id' => $program->id,
            'activity_type_id' => $activityType->id,
            'instructor_id' => $professor->id,
            'name' => 'Edición asignada al profesor',
            'slug' => 'edicion-asignada-al-profesor',
            'edition_number' => 1,
            'status' => 'en_curso',
            'duration_hours' => 12,
        ]);

        $publicEdition = Activity::create([
            'training_program_id' => $program->id,
            'activity_type_id' => $activityType->id,
            'instructor_id' => $otherProfessor->id,
            'name' => 'Edición de otro profesor',
            'slug' => 'edicion-de-otro-profesor',
            'edition_number' => 2,
            'status' => 'publicado',
            'duration_hours' => 12,
        ]);

        $this->actingAs($professor)
            ->get(route('participant.catalog.index'))
            ->assertOk()
            ->assertSee('Edición de otro profesor')
            ->assertDontSee('Edición asignada al profesor');

        $this->actingAs($professor)
            ->get(route('participant.professor.teaching.index'))
            ->assertOk()
            ->assertSee('Edición asignada al profesor')
            ->assertDontSee('Edición de otro profesor');

        $this->actingAs($professor)
            ->post(route('participant.catalog.enroll', $publicEdition))
            ->assertRedirect(route('participant.my-courses.index'));

        $this->actingAs($professor)
            ->get(route('participant.my-courses.index'))
            ->assertOk()
            ->assertSee('Edición de otro profesor')
            ->assertDontSee('Edición asignada al profesor');

        $this->actingAs($professor)
            ->post(route('participant.catalog.enroll', $assignedEdition))
            ->assertStatus(422);
    }

    public function test_a_professor_can_upload_evidence_for_a_course_they_take(): void
    {
        Storage::fake('local');
        Role::findOrCreate('Profesor');

        $professor = User::factory()->create(['status' => 'activo']);
        $professor->assignRole('Profesor');

        $activityType = ActivityType::create([
            'name' => 'Curso',
            'status' => 'activo',
        ]);

        $activity = Activity::create([
            'activity_type_id' => $activityType->id,
            'name' => 'Curso tomado por profesor',
            'slug' => 'curso-tomado-por-profesor',
            'status' => 'publicado',
            'duration_hours' => 8,
        ]);

        $enrollment = Enrollment::create([
            'user_id' => $professor->id,
            'activity_id' => $activity->id,
            'status' => 'aprobada',
            'completion_status' => 'no_iniciado',
        ]);

        $this->actingAs($professor)
            ->post(route('participant.evidences.store', $enrollment), [
                'title' => 'Evidencia del profesor participante',
                'evidence_type' => 'producto',
                'description' => 'Trabajo realizado como participante.',
                'file' => UploadedFile::fake()->create('evidencia.pdf', 100, 'application/pdf'),
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('evidences', [
            'user_id' => $professor->id,
            'activity_id' => $activity->id,
            'enrollment_id' => $enrollment->id,
            'title' => 'Evidencia del profesor participante',
            'status' => 'pendiente',
        ]);

        $this->actingAs($professor)
            ->get(route('participant.professor.profile'))
            ->assertOk()
            ->assertSee('Mis insignias')
            ->assertDontSee('Evidencia del profesor participante');
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
            ->assertSee('Administra cuentas, roles, perfiles y adscripción institucional')
            ->assertSee('Directorio de usuarios')
            ->assertSee('Selecciona un rol para consultar usuarios')
            ->assertSee('Tipo de usuario')
            ->assertSee('Estado')
            ->assertSee('pf-save-confirmation', false);

        $this->actingAs($user)
            ->get(AreaResource::getUrl())
            ->assertOk()
            ->assertSee('Áreas')
            ->assertSee('Organiza las áreas institucionales')
            ->assertSee('Catálogo de áreas')
            ->assertSee('Tipo de área')
            ->assertSee('Responsable')
            ->assertSee('Estado');
    }

    public function test_the_users_table_starts_empty_and_can_filter_or_search_all_users(): void
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

        $this->actingAs($administrator);

        Livewire::test(ManageUsers::class)
            ->assertTableFilterExists('role')
            ->assertTableFilterExists('user_type')
            ->assertTableFilterExists('status')
            ->assertCanNotSeeTableRecords([$administrator, $otherAdministrator, $humanResourcesUser])
            ->filterTable('role', 'Recursos Humanos')
            ->assertCanSeeTableRecords([$humanResourcesUser])
            ->assertCanNotSeeTableRecords([$administrator, $otherAdministrator])
            ->assertTableActionHasLabel('edit', 'Editar', $humanResourcesUser)
            ->assertTableActionHasIcon('edit', Heroicon::OutlinedPencilSquare, $humanResourcesUser)
            ->resetTableFilters()
            ->searchTable('Otro superadministrador')
            ->assertCanSeeTableRecords([$otherAdministrator])
            ->assertCanNotSeeTableRecords([$administrator, $humanResourcesUser]);

        Livewire::test(ManageUsers::class)
            ->assertTableActionHasLabel('create', 'Nuevo usuario')
            ->assertTableActionHasIcon('create', Heroicon::OutlinedUserPlus)
            ->assertTableActionExists('create', fn (Action $action): bool => $action->getModalSubmitActionLabel() === 'Guardar'
                && $action->getModalSubmitAction()?->getIcon() === Heroicon::OutlinedCheck
                && $action->getModalSubmitAction()?->getExtraAttributeBag()->get('data-pf-confirm-save') === 'true'
                && filled($action->getModalSubmitAction()?->getExtraAttributeBag()->get('x-on:click.prevent.stop')));

        Livewire::test(ManageAreas::class)
            ->assertTableActionHasLabel('create', 'Nueva área')
            ->assertTableActionHasIcon('create', Heroicon::OutlinedBuildingOffice2);
    }

    public function test_each_operational_role_can_open_its_dashboard(): void
    {
        foreach (User::FILAMENT_ROLES as $role) {
            Role::findOrCreate($role);
            $user = User::factory()->create(['status' => 'activo']);
            $user->assignRole($role);

            $this->actingAs($user)
                ->get(route('dashboard'))
                ->assertRedirect(route('filament.admin.pages.dashboard'));

            $this->actingAs($user)
                ->get(route('filament.admin.pages.dashboard'))
                ->assertOk()
                ->assertSee($role);
        }

        Role::findOrCreate('Externo');
        $external = User::factory()->create(['status' => 'activo']);
        $external->assignRole('Externo');

        $this->actingAs($external)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Pagos pendientes');
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
            ->get(TrainingProgramResource::getUrl())
            ->assertOk()
            ->assertSee('Gestión de formación')
            ->assertSee('Programas de formación')
            ->assertDontSee('Rúbricas')
            ->assertDontSee('Encuestas')
            ->assertDontSee('Cursos y actividades')
            ->assertDontSee('Ediciones');

        $this->assertFalse(ActivityResource::shouldRegisterNavigation());

        $this->actingAs($user)
            ->get(route('filament.admin.pages.dashboard'))
            ->assertOk()
            ->assertDontSee('Cursos y actividades');

        Livewire::test(ManageTrainingPrograms::class)
            ->assertTableFilterExists('activity_type_id')
            ->assertTableFilterExists('area_id')
            ->assertTableFilterExists('default_modality')
            ->assertTableFilterExists('status')
            ->assertTableActionHasLabel('create', 'Nuevo programa')
            ->assertTableActionHasIcon('create', Heroicon::OutlinedPlus)
            ->assertTableActionHasLabel('administrar', 'Administrar', $program)
            ->assertTableActionHasIcon('administrar', Heroicon::OutlinedArrowRight, $program)
            ->assertTableActionDoesNotExist('ediciones');

        $this->actingAs($user)
            ->get(ProgramControlPage::getUrl(['record' => $program->getKey()]))
            ->assertOk()
            ->assertSee('pf-program-breadcrumbs', false)
            ->assertSee('Gestión de formación')
            ->assertSee('Programas de formación')
            ->assertSee('Control del programa')
            ->assertSee('Volver a programas')
            ->assertSee('pf-program-back', false)
            ->assertDontSee('fi-header-heading', false)
            ->assertSee('Inscripciones')
            ->assertSee('Asistencia')
            ->assertSee('Evidencias')
            ->assertSee('Evaluaciones')
            ->assertSee('Encuesta de satisfacción')
            ->assertSee('Archivos')
            ->assertSee('Constancias')
            ->assertDontSee('Edición')
            ->assertDontSee('fi-page-sub-navigation-tabs', false);

        $this->actingAs($user)
            ->get(EnrollmentResource::getUrl(parameters: [
                'activity' => $edition->id,
                'training_program' => $program->id,
            ]))
            ->assertOk()
            ->assertSee('Gestionando '.$program->name)
            ->assertSee('Volver al programa')
            ->assertSee('Programa de formación')
            ->assertDontSee('fi-page-sub-navigation-tabs', false);

        $programWithoutOperation = TrainingProgram::create([
            'activity_type_id' => $activityType->id,
            'name' => 'Programa sin nivel intermedio',
            'slug' => 'programa-sin-nivel-intermedio',
            'duration_hours' => 6,
            'status' => 'activo',
        ]);

        $this->assertDatabaseMissing('activities', [
            'training_program_id' => $programWithoutOperation->id,
        ]);

        $this->actingAs($user)
            ->get(ProgramControlPage::getUrl(['record' => $programWithoutOperation->getKey()]))
            ->assertOk()
            ->assertSee('Control del programa');

        $this->assertDatabaseHas('activities', [
            'training_program_id' => $programWithoutOperation->id,
            'name' => $programWithoutOperation->name,
        ]);

        $this->actingAs($user)
            ->get(SurveyQuestionResource::getUrl())
            ->assertOk()
            ->assertSee('Preguntas de satisfacción')
            ->assertSee('todos los cursos');

        Livewire::test(ManageSurveyQuestions::class)
            ->assertTableFilterExists('question_type')
            ->assertTableFilterExists('is_required')
            ->assertTableActionHasLabel('create', 'Nueva pregunta')
            ->callTableAction('create', data: [
                'question_text' => '¿Recomendarías este curso?',
                'question_type' => 'escala',
                'options' => ['1' => '1', '5' => '5'],
                'is_required' => true,
                'order_number' => 20,
            ])
            ->assertHasNoTableActionErrors();

        $generalSurvey = Survey::query()->where('is_general', true)->firstOrFail();

        $this->assertDatabaseHas('survey_questions', [
            'survey_id' => $generalSurvey->id,
            'question_text' => '¿Recomendarías este curso?',
        ]);

        $this->actingAs($user)
            ->get(EditionControlPage::getUrl(['record' => $edition->getKey()]))
            ->assertOk()
            ->assertSee('Control por edición')
            ->assertSee('Encuesta de satisfacción');

        $this->actingAs($user)
            ->get(SurveyResponseResource::getUrl(parameters: ['activity' => $edition->id]))
            ->assertOk()
            ->assertSee('Encuesta de satisfacción')
            ->assertSee('Aún no hay respuestas');

        $this->actingAs($user)
            ->get(EditionControlPage::getUrl(['record' => $edition]))
            ->assertOk();
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
