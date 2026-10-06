<?php

namespace Tests\Feature;

use App\Filament\Pages\LearningPathControlPage;
use App\Filament\Resources\CertificationProgramResource;
use App\Filament\Resources\CertificationProgramResource\Pages\EditCertificationProgram;
use App\Filament\Resources\CertificationProgramResource\RelationManagers\DiplomaProgramsRelationManager as CertificationDiplomasRelationManager;
use App\Filament\Resources\CompetencyResource;
use App\Filament\Resources\DiplomaProgramResource;
use App\Filament\Resources\DiplomaProgramResource\Pages\EditDiplomaProgram;
use App\Filament\Resources\LearningPathResource;
use App\Filament\Resources\LearningPathResource\Pages\EditLearningPath;
use App\Filament\Resources\LearningPathResource\Pages\ManageLearningPaths;
use App\Filament\Resources\LearningPathResource\RelationManagers\TrainingProgramsRelationManager;
use App\Filament\Resources\MicrocredentialResource;
use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\CertificationProgram;
use App\Models\DiplomaProgram;
use App\Models\LearningPath;
use App\Models\Microcredential;
use App\Models\TrainingProgram;
use App\Models\User;
use App\Models\UserLearningPath;
use App\Services\LearningPaths\LearningPathProgressService;
use Filament\Support\Icons\Heroicon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LearningPathFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_assigning_a_learning_path_enrolls_the_participant_and_tracks_progress(): void
    {
        $participant = User::factory()->create(['status' => 'activo']);
        Role::findOrCreate('Profesor');
        $participant->assignRole('Profesor');

        $type = ActivityType::create(['name' => 'Curso', 'status' => 'activo']);
        $first = $this->activity($type, 'Actividad inicial', 'actividad-inicial');
        $second = $this->activity($type, 'Actividad avanzada', 'actividad-avanzada');

        $path = LearningPath::create([
            'name' => 'Ruta secuencial',
            'slug' => 'ruta-secuencial',
            'description' => 'Ruta para prueba.',
            'total_hours' => 10,
            'is_sequential' => true,
            'status' => 'publicada',
        ]);

        $path->items()->create([
            'activity_id' => $first->id,
            'order_number' => 1,
            'is_required' => true,
            'minimum_score' => 80,
        ]);
        $path->items()->create([
            'activity_id' => $second->id,
            'order_number' => 2,
            'is_required' => true,
            'minimum_score' => 80,
        ]);

        $assignment = UserLearningPath::create([
            'user_id' => $participant->id,
            'learning_path_id' => $path->id,
        ]);

        $this->assertDatabaseCount('enrollments', 2);

        $participant->enrollments()
            ->where('activity_id', $first->id)
            ->update([
                'completion_status' => 'completado',
                'final_score' => 9,
                'completed_at' => now(),
            ]);

        app(LearningPathProgressService::class)->synchronizeAssignment($assignment);

        $this->assertSame('50.00', $assignment->refresh()->progress_percentage);
        $this->assertSame('en_progreso', $assignment->status);

        $this->actingAs($participant)
            ->get(route('participant.learning-paths.show', $path))
            ->assertOk()
            ->assertSee('Ruta secuencial')
            ->assertSee('50%')
            ->assertSee('Actividad inicial')
            ->assertSee('Actividad avanzada');
    }

    public function test_an_administrator_can_open_learning_path_management(): void
    {
        $administrator = User::factory()->create(['status' => 'activo']);
        Role::findOrCreate('Superadministrador');
        $administrator->assignRole('Superadministrador');

        $this->actingAs($administrator)
            ->get(LearningPathResource::getUrl())
            ->assertOk()
            ->assertSee('Rutas de aprendizaje')
            ->assertSee('Todas las rutas')
            ->assertSee('Publicadas')
            ->assertSee('Borradores')
            ->assertSee('Nueva ruta');

        Livewire::test(ManageLearningPaths::class)
            ->assertTableFilterExists('generates_microcredential')
            ->assertTableFilterExists('area_id')
            ->assertTableFilterExists('status')
            ->assertTableActionHasLabel('create', 'Nueva ruta')
            ->assertTableActionHasIcon('create', Heroicon::OutlinedPlus);

        $this->assertTrue(CertificationProgramResource::shouldRegisterNavigation());
        $this->assertTrue(DiplomaProgramResource::shouldRegisterNavigation());
        $this->assertFalse(CompetencyResource::shouldRegisterNavigation());
    }

    public function test_a_training_program_can_be_reused_and_managed_inside_learning_paths(): void
    {
        $administrator = User::factory()->create(['status' => 'activo']);
        Role::findOrCreate('Superadministrador');
        $administrator->assignRole('Superadministrador');

        $participant = User::factory()->create(['status' => 'activo']);
        Role::findOrCreate('Alumno');
        $participant->assignRole('Alumno');

        $type = ActivityType::create(['name' => 'Curso', 'status' => 'activo']);
        $program = TrainingProgram::create([
            'activity_type_id' => $type->id,
            'name' => 'Curso reutilizable',
            'slug' => 'curso-reutilizable',
            'duration_hours' => 8,
            'status' => 'activo',
        ]);

        $certification = LearningPath::create([
            'name' => 'Certificación docente',
            'slug' => 'certificacion-docente',
            'path_type' => 'certificacion',
            'total_hours' => 8,
            'status' => 'publicada',
        ]);
        $microcredential = LearningPath::create([
            'name' => 'Microcredencial docente',
            'slug' => 'microcredencial-docente',
            'path_type' => 'microcredencial',
            'total_hours' => 8,
            'status' => 'publicada',
        ]);

        foreach ([$certification, $microcredential] as $path) {
            $path->trainingPrograms()->attach($program->id, [
                'order_number' => 1,
                'is_required' => true,
                'minimum_score' => 80,
            ]);
        }

        UserLearningPath::create([
            'user_id' => $participant->id,
            'learning_path_id' => $certification->id,
        ]);

        $this->assertDatabaseCount('learning_path_training_program', 2);
        $this->assertDatabaseHas('activities', [
            'training_program_id' => $program->id,
        ]);
        $this->assertDatabaseHas('enrollments', [
            'user_id' => $participant->id,
            'activity_id' => $program->activities()->value('id'),
        ]);
        $this->assertFalse($certification->refresh()->generates_microcredential);
        $this->assertTrue($microcredential->refresh()->generates_microcredential);

        $controlUrl = LearningPathControlPage::getUrl(['record' => $certification->id]);
        $controlResponse = $this->actingAs($administrator)->get($controlUrl);
        $this->assertSame(200, $controlResponse->status(), 'No se pudo abrir el control: '.$controlUrl);

        $controlResponse
            ->assertSee('Control de la ruta')
            ->assertSee('Certificación docente')
            ->assertDontSee('Crea nuevas competencias o vincula las que ya existen.')
            ->assertSee('Programas de formación')
            ->assertSee('Participantes')
            ->assertSee('Editar información')
            ->assertSee('Volver a rutas')
            ->assertDontSee('fi-header-heading', false);

        $editUrl = LearningPathResource::getUrl('edit', [
            'record' => $certification,
            'relation' => '2',
        ]);
        $editResponse = $this->actingAs($administrator)->get($editUrl);
        $this->assertSame(200, $editResponse->status(), 'No se pudo abrir la administración: '.$editUrl);

        $editResponse
            ->assertSee('Programas de formación');

        Livewire::test(TrainingProgramsRelationManager::class, [
            'ownerRecord' => $certification,
            'pageClass' => EditLearningPath::class,
        ])
            ->assertCanSeeTableRecords([$program])
            ->assertTableActionHasLabel('attach', 'Agregar programa')
            ->assertTableActionHasLabel('administrar_programa', 'Abrir programa', $program)
            ->assertTableActionHasLabel('configurar_vinculo', 'Configurar', $program)
            ->callTableAction('configurar_vinculo', $program, data: [
                'order_number' => 2,
                'is_required' => 1,
                'minimum_score' => 90,
            ])
            ->assertHasNoTableActionErrors();

        $this->assertDatabaseHas('learning_path_training_program', [
            'learning_path_id' => $certification->id,
            'training_program_id' => $program->id,
            'order_number' => 2,
            'minimum_score' => 90,
        ]);
    }

    public function test_a_route_combines_diplomas_certifications_and_direct_programs_with_independent_badges(): void
    {
        $participant = User::factory()->create(['status' => 'activo']);
        Role::findOrCreate('Alumno');
        $participant->assignRole('Alumno');
        $type = ActivityType::create(['name' => 'Curso', 'status' => 'activo']);

        $diplomaCourse = TrainingProgram::create([
            'activity_type_id' => $type->id,
            'name' => 'Curso del diplomado',
            'slug' => 'curso-del-diplomado',
            'duration_hours' => 4,
            'generates_microcredential' => true,
            'status' => 'activo',
        ]);
        $certificationCourse = TrainingProgram::create([
            'activity_type_id' => $type->id,
            'name' => 'Curso de certificación',
            'slug' => 'curso-de-certificacion',
            'duration_hours' => 4,
            'status' => 'activo',
        ]);
        $directCourse = TrainingProgram::create([
            'activity_type_id' => $type->id,
            'name' => 'Taller directo',
            'slug' => 'taller-directo',
            'duration_hours' => 4,
            'status' => 'activo',
        ]);

        $diploma = DiplomaProgram::create([
            'name' => 'Diplomado integrado',
            'slug' => 'diplomado-integrado',
            'status' => 'activo',
            'generates_microcredential' => true,
        ]);
        $diploma->trainingPrograms()->attach($diplomaCourse, [
            'order_number' => 1, 'is_required' => true, 'minimum_score' => 80,
        ]);
        $certification = CertificationProgram::create([
            'name' => 'Certificación integrada',
            'slug' => 'certificacion-integrada',
            'status' => 'activo',
            'generates_microcredential' => true,
        ]);
        $certification->trainingPrograms()->attach($certificationCourse, [
            'order_number' => 1, 'is_required' => true, 'minimum_score' => 80,
        ]);
        $certification->diplomaPrograms()->attach($diploma, [
            'order_number' => 2, 'is_required' => true,
        ]);
        $path = LearningPath::create([
            'name' => 'Ruta mixta',
            'slug' => 'ruta-mixta',
            'status' => 'publicada',
            'is_sequential' => true,
            'generates_microcredential' => true,
        ]);
        $path->diplomaPrograms()->attach($diploma, ['order_number' => 1, 'is_required' => true]);
        $path->certificationPrograms()->attach($certification, ['order_number' => 2, 'is_required' => true]);
        $path->trainingPrograms()->attach($directCourse, [
            'order_number' => 3, 'is_required' => true, 'minimum_score' => null,
        ]);

        $assignment = UserLearningPath::create(['user_id' => $participant->id, 'learning_path_id' => $path->id]);
        $this->assertDatabaseCount('enrollments', 3);
        $progress = app(LearningPathProgressService::class);
        $this->assertFalse($progress->canAccessActivity($participant, $directCourse->activities()->firstOrFail()));

        $participant->enrollments()->whereHas('activity', fn ($query) => $query
            ->where('training_program_id', $diplomaCourse->id))
            ->firstOrFail()->update(['completion_status' => 'completado', 'final_score' => 9]);

        $this->assertSame('33.33', $assignment->refresh()->progress_percentage);
        $this->assertTrue($progress->canAccessActivity($participant, $certificationCourse->activities()->firstOrFail()));
        $this->assertFalse($progress->canAccessActivity($participant, $directCourse->activities()->firstOrFail()));
        $this->assertDatabaseHas('microcredentials', ['user_id' => $participant->id, 'diploma_program_id' => $diploma->id]);
        $this->assertDatabaseHas('microcredentials', ['user_id' => $participant->id, 'activity_id' => $diplomaCourse->activities()->value('id')]);

        $participant->enrollments()->whereHas('activity', fn ($query) => $query
            ->where('training_program_id', $certificationCourse->id))
            ->firstOrFail()->update(['completion_status' => 'completado', 'final_score' => 90]);
        $this->assertDatabaseHas('microcredentials', ['user_id' => $participant->id, 'certification_program_id' => $certification->id]);

        $participant->enrollments()->whereHas('activity', fn ($query) => $query
            ->where('training_program_id', $directCourse->id))
            ->firstOrFail()->update(['completion_status' => 'completado']);

        $this->assertSame('completada', $assignment->refresh()->status);
        $this->assertDatabaseHas('microcredentials', ['user_id' => $participant->id, 'learning_path_id' => $path->id]);
        $this->assertSame(4, Microcredential::where('user_id', $participant->id)->count());
        $this->actingAs($participant)->get(route('participant.badges.index'))
            ->assertOk()->assertSee('Diplomado integrado')->assertSee('Certificación integrada');
    }

    public function test_a_sequential_route_blocks_direct_access_to_later_learning_and_evidence(): void
    {
        $participant = User::factory()->create(['status' => 'activo']);
        Role::findOrCreate('Alumno');
        $participant->assignRole('Alumno');
        $type = ActivityType::create(['name' => 'Curso', 'status' => 'activo']);
        $first = $this->activity($type, 'Primera actividad', 'primera-actividad');
        $second = $this->activity($type, 'Segunda actividad', 'segunda-actividad');
        $path = LearningPath::create([
            'name' => 'Ruta con orden', 'slug' => 'ruta-con-orden',
            'status' => 'publicada', 'is_sequential' => true,
        ]);
        $path->items()->create(['activity_id' => $first->id, 'order_number' => 1, 'is_required' => true]);
        $path->items()->create(['activity_id' => $second->id, 'order_number' => 2, 'is_required' => true]);
        UserLearningPath::create(['user_id' => $participant->id, 'learning_path_id' => $path->id]);
        $laterEnrollment = $participant->enrollments()->where('activity_id', $second->id)->firstOrFail();

        $this->actingAs($participant)->get(route('participant.learning.show', $laterEnrollment))->assertForbidden();

        $participant->enrollments()->where('activity_id', $first->id)
            ->firstOrFail()->update(['completion_status' => 'completado']);

        $this->actingAs($participant)->get(route('participant.learning.show', $laterEnrollment))->assertOk();
    }

    public function test_diplomas_and_badges_are_managed_from_the_learning_paths_section(): void
    {
        $administrator = User::factory()->create(['status' => 'activo']);
        Role::findOrCreate('Superadministrador');
        $administrator->assignRole('Superadministrador');
        $type = ActivityType::create(['name' => 'Curso', 'status' => 'activo']);
        $program = TrainingProgram::create([
            'activity_type_id' => $type->id, 'name' => 'Curso vinculado',
            'slug' => 'curso-vinculado', 'duration_hours' => 8, 'status' => 'activo',
        ]);
        $diploma = DiplomaProgram::create([
            'name' => 'Diplomado gestionable', 'slug' => 'diplomado-gestionable', 'status' => 'activo',
        ]);
        $diploma->trainingPrograms()->attach($program, [
            'order_number' => 1, 'is_required' => true, 'minimum_score' => 80,
        ]);
        $certification = CertificationProgram::create([
            'name' => 'Certificación gestionable', 'slug' => 'certificacion-gestionable', 'status' => 'activo',
        ]);
        $certification->diplomaPrograms()->attach($diploma, ['order_number' => 1, 'is_required' => true]);

        $this->actingAs($administrator)
            ->get(DiplomaProgramResource::getUrl('edit', ['record' => $diploma]))
            ->assertOk()->assertSee('Diplomado gestionable');
        $this->actingAs($administrator)
            ->get(MicrocredentialResource::getUrl())
            ->assertOk()->assertSee('Microcredenciales');
        $this->actingAs($administrator)
            ->get(CertificationProgramResource::getUrl('edit', ['record' => $certification]))
            ->assertOk()->assertSee('Certificación gestionable');
        $this->assertStringContainsString('/rutas-aprendizaje/', MicrocredentialResource::getUrl());

        Livewire::test(TrainingProgramsRelationManager::class, [
            'ownerRecord' => $diploma,
            'pageClass' => EditDiplomaProgram::class,
        ])->assertCanSeeTableRecords([$program]);
        Livewire::test(CertificationDiplomasRelationManager::class, [
            'ownerRecord' => $certification,
            'pageClass' => EditCertificationProgram::class,
        ])->assertCanSeeTableRecords([$diploma]);
    }

    private function activity(ActivityType $type, string $name, string $slug): Activity
    {
        return Activity::create([
            'activity_type_id' => $type->id,
            'name' => $name,
            'slug' => $slug,
            'duration_hours' => 5,
            'status' => 'publicado',
        ]);
    }
}
