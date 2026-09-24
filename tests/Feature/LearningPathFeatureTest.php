<?php

namespace Tests\Feature;

use App\Filament\Pages\LearningPathControlPage;
use App\Filament\Resources\CertificationProgramResource;
use App\Filament\Resources\CompetencyResource;
use App\Filament\Resources\DiplomaProgramResource;
use App\Filament\Resources\LearningPathResource;
use App\Filament\Resources\LearningPathResource\Pages\EditLearningPath;
use App\Filament\Resources\LearningPathResource\Pages\ManageLearningPaths;
use App\Filament\Resources\LearningPathResource\RelationManagers\TrainingProgramsRelationManager;
use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\LearningPath;
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
            ->assertSee('Certificaciones')
            ->assertSee('Diplomados')
            ->assertSee('Microcredenciales')
            ->assertSee('Nueva ruta');

        Livewire::test(ManageLearningPaths::class)
            ->assertTableFilterExists('path_type')
            ->assertTableFilterExists('area_id')
            ->assertTableFilterExists('status')
            ->assertTableActionHasLabel('create', 'Nueva ruta')
            ->assertTableActionHasIcon('create', Heroicon::OutlinedPlus);

        $this->assertFalse(CertificationProgramResource::shouldRegisterNavigation());
        $this->assertFalse(DiplomaProgramResource::shouldRegisterNavigation());
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
            ->assertSee('Competencias')
            ->assertSee('Programas de formación')
            ->assertSee('Participantes')
            ->assertSee('Editar información')
            ->assertSee('Volver a rutas')
            ->assertDontSee('fi-header-heading', false);

        $editUrl = LearningPathResource::getUrl('edit', [
            'record' => $certification,
            'relation' => '1',
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
