<?php

namespace Tests\Feature;

use App\Filament\Pages\LearningPathControlPage;
use App\Filament\Pages\ProgramControlPage;
use App\Filament\Resources\LearningPathResource\Pages\ManageLearningPaths;
use App\Models\ActivityType;
use App\Models\Area;
use App\Models\LearningPath;
use App\Models\TrainingProgram;
use App\Models\User;
use App\Models\UserLearningPath;
use Filament\Forms\Components\Select;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LearningPathProgramSelectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->user('Superadministrador'));
    }

    public function test_each_type_opens_its_own_selection_window_only_in_the_learning_path(): void
    {
        $path = $this->path('Ruta con selección');
        $programs = collect(['Minicurso', 'Curso', 'Taller'])->mapWithKeys(
            fn (string $type): array => [$type => $this->program($type, $type.' disponible')],
        );

        $page = Livewire::test(LearningPathControlPage::class, ['record' => $path->id]);

        foreach ($this->actions() as $type => $action) {
            $page->mountAction($action)
                ->assertActionMounted($action)
                ->assertFormFieldExists('program_ids', fn (Select $field): bool => $field->isMultiple()
                    && $field->getOptions() === [$programs[$type]->id => $programs[$type]->name])
                ->unmountAction();
        }

        $this->get(ProgramControlPage::getUrl(['record' => $programs['Curso']->id]))
            ->assertOk()
            ->assertDontSee("mountAction('addCourses')", false)
            ->assertDontSee("mountAction('addMiniCourses')", false)
            ->assertDontSee("mountAction('addWorkshops')", false);
    }

    public function test_administrar_opens_the_selection_window_for_an_existing_route_and_keeps_added_programs_visible(): void
    {
        $path = $this->path('Ruta creada anteriormente');
        $existing = $this->program('Curso', 'Curso ya agregado');
        $path->trainingPrograms()->attach($existing, ['order_number' => 1, 'is_required' => true]);

        $list = Livewire::test(ManageLearningPaths::class);
        $url = $list->instance()->getTable()->getAction('administrar')->record($path)->getUrl();
        parse_str(parse_url($url, PHP_URL_QUERY), $query);
        $this->assertSame('managePrograms', $query['action']);
        $this->assertStringNotContainsString('/editar', $url);

        $page = Livewire::withQueryParams($query)->test(LearningPathControlPage::class, ['record' => $path->id])
            ->assertSet('defaultAction', 'managePrograms')
            ->mountAction('managePrograms')
            ->assertActionMounted('managePrograms')
            ->assertDontSee('Selecciona los minicursos que quieres agregar a esta ruta.');

        $modal = $page->getMountedActionModalHtml();
        foreach (['Curso ya agregado', 'Agregar minicursos', 'Agregar cursos', 'Agregar talleres'] as $label) {
            $this->assertStringContainsString($label, $modal);
        }
        $this->assertStringNotContainsString('Ver programas de la ruta', $modal);

        foreach ($this->actions() as $type => $action) {
            $program = $this->program($type, $type.' agregado después');
            $page->mountAction($action)
                ->assertActionMounted(['managePrograms', $action])
                ->setActionData(['program_ids' => [$program->id]])
                ->callMountedAction()
                ->assertHasNoErrors()
                ->assertActionMounted('managePrograms')
                ->assertNoRedirect();
            $this->assertStringContainsString($program->name, $page->getMountedActionModalHtml());
        }

        $page->unmountAction()->mountAction('managePrograms');
        $modal = $page->getMountedActionModalHtml();
        foreach (['Curso ya agregado', 'Minicurso agregado después', 'Curso agregado después', 'Taller agregado después'] as $name) {
            $this->assertStringContainsString($name, $modal);
        }
        $this->assertSame(4, $path->trainingPrograms()->count());
    }

    public function test_cancelling_an_addition_returns_to_administrar_without_changing_the_route(): void
    {
        $path = $this->path('Ruta para administrar');
        $course = $this->program('Curso', 'Curso sin confirmar');

        $page = Livewire::test(LearningPathControlPage::class, ['record' => $path->id])
            ->mountAction('managePrograms')
            ->mountAction('addCourses')
            ->setActionData(['program_ids' => [$course->id]])
            ->unmountAction()
            ->assertActionMounted('managePrograms')
            ->assertNoRedirect();

        $this->assertStringContainsString('Esta ruta aún no tiene cursos, minicursos ni talleres.', $page->getMountedActionModalHtml());
        $this->assertDatabaseCount('learning_path_training_program', 0);
    }

    public function test_multiple_programs_of_each_type_are_added_only_to_the_selected_path(): void
    {
        $path = $this->path('Ruta seleccionada');
        $otherPath = $this->path('Otra ruta');
        $existing = $this->program('Curso', 'Curso existente');
        $path->trainingPrograms()->attach($existing, ['order_number' => 5, 'is_required' => true, 'minimum_score' => 90]);
        $otherPath->trainingPrograms()->attach($existing, ['order_number' => 1, 'is_required' => true]);
        $page = Livewire::test(LearningPathControlPage::class, ['record' => $path->id]);
        $order = 6;

        foreach ($this->actions() as $type => $action) {
            $first = $this->program($type, $type.' A');
            $second = $this->program($type, $type.' B');
            $page->callAction($action, data: [
                'program_ids' => [$second->id, $first->id],
                'order_number' => $order,
                'is_required' => 0,
                'minimum_score' => 75,
            ])->assertHasNoActionErrors()->assertNotified('Programas agregados a la ruta');

            foreach ([$second, $first] as $index => $program) {
                $this->assertDatabaseHas('learning_path_training_program', [
                    'learning_path_id' => $path->id,
                    'training_program_id' => $program->id,
                    'order_number' => $order + $index,
                    'is_required' => false,
                    'minimum_score' => 75,
                ]);
            }

            $order += 2;
        }

        $this->assertSame(7, $path->trainingPrograms()->count());
        $this->assertSame([$existing->id], $otherPath->trainingPrograms()->pluck('training_programs.id')->all());
        $this->assertDatabaseHas('learning_path_training_program', [
            'learning_path_id' => $path->id, 'training_program_id' => $existing->id,
            'order_number' => 5, 'is_required' => true, 'minimum_score' => 90,
        ]);
        $this->assertSame(2, $page->instance()->record->mini_courses_count);
        $this->assertSame(3, $page->instance()->record->courses_count);
        $this->assertSame(2, $page->instance()->record->workshops_count);
    }

    public function test_cancelling_a_window_does_not_add_programs(): void
    {
        $path = $this->path('Ruta sin cambios');

        foreach ($this->actions() as $type => $action) {
            $program = $this->program($type, $type.' sin guardar');
            Livewire::test(LearningPathControlPage::class, ['record' => $path->id])
                ->mountAction($action)
                ->setActionData(['program_ids' => [$program->id]])
                ->unmountAction();
        }

        $this->assertDatabaseCount('learning_path_training_program', 0);
        $this->assertDatabaseCount('enrollments', 0);
    }

    public function test_unavailable_or_wrong_type_programs_are_rejected_without_partial_additions(): void
    {
        $path = $this->path('Ruta con validación');
        $valid = $this->program('Curso', 'Curso disponible');
        $wrongType = $this->program('Taller', 'Taller de otro tipo');
        $inactive = $this->program('Curso', 'Curso inactivo', ['status' => 'inactivo']);
        $deleted = $this->program('Curso', 'Curso eliminado');
        $deleted->delete();
        $linked = $this->program('Curso', 'Curso ya vinculado');
        $path->trainingPrograms()->attach($linked, ['order_number' => 1, 'is_required' => true]);

        foreach ([$wrongType->id, $inactive->id, $deleted->id, $linked->id, 999999] as $invalidId) {
            Livewire::test(LearningPathControlPage::class, ['record' => $path->id])
                ->callAction('addCourses', data: ['program_ids' => [$valid->id, $invalidId]])
                ->assertHasActionErrors();

            $this->assertSame([$linked->id], $path->trainingPrograms()->pluck('training_programs.id')->all());
        }
    }

    public function test_area_managers_can_only_select_and_attach_programs_visible_to_them(): void
    {
        $area = Area::create(['name' => 'Área propia', 'area_type' => 'academica', 'status' => 'activa']);
        $otherArea = Area::create(['name' => 'Otra área', 'area_type' => 'academica', 'status' => 'activa']);
        $path = $this->path('Ruta del área', ['area_id' => $area->id]);
        $visible = $this->program('Curso', 'Curso propio', ['area_id' => $area->id]);
        $hidden = $this->program('Curso', 'Curso ajeno', ['area_id' => $otherArea->id]);
        $this->actingAs($this->user('Responsable Area', ['area_id' => $area->id]));

        Livewire::test(LearningPathControlPage::class, ['record' => $path->id])
            ->mountAction('addCourses')
            ->assertFormFieldExists('program_ids', fn (Select $field): bool => $field->getOptions() === [$visible->id => $visible->name])
            ->setActionData(['program_ids' => [$hidden->id]])
            ->callMountedAction()
            ->assertHasActionErrors();

        $this->assertDatabaseCount('learning_path_training_program', 0);
    }

    public function test_read_only_users_cannot_add_programs(): void
    {
        $path = $this->path('Ruta de consulta');
        $this->actingAs($this->user('Calidad Academica'));
        $page = Livewire::test(LearningPathControlPage::class, ['record' => $path->id]);

        foreach (['managePrograms', ...array_values($this->actions())] as $action) {
            $page->assertActionHidden($action)
                ->call('mountAction', $action)
                ->assertActionNotMounted($action);
        }

        $this->assertDatabaseCount('learning_path_training_program', 0);
    }

    public function test_adding_programs_updates_existing_participant_assignments_and_the_route_view(): void
    {
        $path = $this->path('Ruta asignada');
        $participant = $this->user('Alumno');
        $assignment = UserLearningPath::create(['user_id' => $participant->id, 'learning_path_id' => $path->id]);
        $programs = [];

        foreach ($this->actions() as $type => $action) {
            $program = $this->program($type, $type.' para participante');
            $programs[] = $program;
            Livewire::test(LearningPathControlPage::class, ['record' => $path->id])
                ->callAction($action, data: ['program_ids' => [$program->id]])
                ->assertHasNoActionErrors();

            $this->assertDatabaseHas('enrollments', [
                'user_id' => $participant->id,
                'activity_id' => $program->activities()->sole()->id,
            ]);
        }

        $this->assertSame('no_iniciada', $assignment->refresh()->status);
        $response = $this->actingAs($participant)->get(route('participant.learning-paths.show', $path))->assertOk();

        foreach ($programs as $program) {
            $response->assertSee($program->name);
        }

        $response->assertDontSee('Agregar a la ruta');
    }

    private function actions(): array
    {
        return ['Minicurso' => 'addMiniCourses', 'Curso' => 'addCourses', 'Taller' => 'addWorkshops'];
    }

    private function user(string $role, array $attributes = []): User
    {
        Role::findOrCreate($role);
        $user = User::factory()->create(['status' => 'activo', ...$attributes]);
        $user->assignRole($role);

        return $user;
    }

    private function path(string $name, array $attributes = []): LearningPath
    {
        return LearningPath::create([
            'name' => $name, 'slug' => str($name)->slug()->toString(), 'status' => 'publicada', ...$attributes,
        ]);
    }

    private function program(string $type, string $name, array $attributes = []): TrainingProgram
    {
        $activityType = ActivityType::firstOrCreate(['name' => $type], ['status' => 'activo']);

        return TrainingProgram::create([
            'activity_type_id' => $activityType->id, 'name' => $name,
            'slug' => str($name)->slug()->toString(), 'duration_hours' => 2, 'status' => 'activo', ...$attributes,
        ]);
    }
}
