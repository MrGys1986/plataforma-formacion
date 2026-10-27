<?php

namespace Tests\Feature;

use App\Filament\Resources\LearningPathResource\Pages\EditLearningPath;
use App\Filament\Resources\LearningPathResource\Pages\ManageLearningPaths;
use App\Filament\Resources\LearningPathResource\RelationManagers\UserLearningPathsRelationManager;
use App\Filament\Resources\UserResource\Pages\ManageUsers;
use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\LearningPath;
use App\Models\User;
use App\Services\Files\ManagedFileService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminUploadWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::findOrCreate('Superadministrador');
        $administrator = User::factory()->create(['status' => 'activo']);
        $administrator->assignRole('Superadministrador');
        $this->actingAs($administrator);
        Storage::fake('public');
    }

    public function test_new_learning_path_action_saves_its_cover_and_generates_the_slug(): void
    {
        Livewire::test(ManageLearningPaths::class)
            ->callTableAction('create', data: [
                'name' => 'Ruta de prueba',
                'slug' => '',
                'status' => 'publicada',
                'cover_file_id' => UploadedFile::fake()->image('ruta.jpg'),
            ])
            ->assertHasNoTableActionErrors();

        $path = LearningPath::where('name', 'Ruta de prueba')->firstOrFail();
        $this->assertSame('ruta-de-prueba', $path->slug);
        $this->assertSame('ruta.jpg', $path->coverFile->original_name);
        Storage::disk('public')->assertExists($path->coverFile->path);

        Role::findOrCreate('Alumno');
        $participant = User::factory()->create(['status' => 'activo']);
        $participant->assignRole('Alumno');
        $coverUrl = asset('storage/'.$path->coverFile->path);
        $this->assertSame($coverUrl, $path->coverFile->optimizedImageUrl());
        foreach ([route('participant.learning-paths.index'), route('participant.learning-paths.show', $path)] as $url) {
            $this->actingAs($participant)->get($url)->assertOk()->assertSee($coverUrl, escape: false);
        }

        $this->get('http://formacion.example.test'.route('participant.learning-paths.index', absolute: false))
            ->assertOk()->assertSee('http://formacion.example.test/storage/'.$path->coverFile->path, escape: false);
    }

    public function test_duplicate_generated_slugs_return_a_validation_error(): void
    {
        LearningPath::create([
            'name' => 'Ruta duplicada', 'slug' => 'ruta-duplicada', 'status' => 'publicada',
        ]);

        Livewire::test(ManageLearningPaths::class)
            ->callTableAction('create', data: [
                'name' => 'Ruta duplicada', 'slug' => '', 'status' => 'publicada',
            ])->assertHasTableActionErrors(['slug' => 'unique']);

        $this->assertDatabaseCount('learning_paths', 1);
    }

    public function test_editing_a_path_preserves_the_existing_cover(): void
    {
        $cover = app(ManagedFileService::class)->store(
            UploadedFile::fake()->image('portada.jpg'), 'learning-path-covers', auth()->id(), true,
        );
        $path = LearningPath::create([
            'name' => 'Ruta con portada', 'slug' => 'ruta-con-portada',
            'status' => 'publicada', 'cover_file_id' => $cover->id,
        ]);

        Livewire::test(EditLearningPath::class, ['record' => $path->public_id])
            ->fillForm(['description' => 'Descripción actualizada'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame($cover->id, $path->fresh()->cover_file_id);
    }

    public function test_new_user_action_saves_the_user_and_roles(): void
    {
        $role = Role::findOrCreate('Alumno');

        Livewire::test(ManageUsers::class)
            ->callTableAction('create', data: [
                'name' => 'Participante de prueba',
                'email' => 'participante@example.test',
                'password' => 'Testing123!',
                'roles' => [$role->id],
                'status' => 'activo',
            ])
            ->assertHasNoTableActionErrors();

        $this->assertTrue(User::where('email', 'participante@example.test')->firstOrFail()->hasRole('Alumno'));
    }

    public function test_a_participant_can_be_assigned_using_the_learning_path_button(): void
    {
        Role::findOrCreate('Alumno');
        $participant = User::factory()->create(['status' => 'activo']);
        $participant->assignRole('Alumno');
        $path = LearningPath::create([
            'name' => 'Ruta asignada', 'slug' => 'ruta-asignada', 'status' => 'publicada',
        ]);

        Livewire::test(UserLearningPathsRelationManager::class, [
            'ownerRecord' => $path, 'pageClass' => EditLearningPath::class,
        ])->callTableAction('create', data: ['user_id' => $participant->id])
            ->assertHasNoTableActionErrors();

        $this->assertDatabaseHas('user_learning_paths', [
            'learning_path_id' => $path->id, 'user_id' => $participant->id,
        ]);
    }

    public function test_participant_course_pages_hide_the_edition_suffix(): void
    {
        Role::findOrCreate('Alumno');
        $participant = User::factory()->create(['status' => 'activo']);
        $participant->assignRole('Alumno');
        $type = ActivityType::create(['name' => 'Curso', 'status' => 'activo']);
        $activity = Activity::create([
            'name' => 'Curso de prueba - Edición de catálogo',
            'slug' => 'curso-de-prueba', 'activity_type_id' => $type->id,
            'status' => 'publicado', 'duration_hours' => 10,
        ]);
        $enrollment = $activity->enrollments()->create([
            'user_id' => $participant->id, 'status' => 'aprobada',
        ]);

        foreach ([
            route('participant.catalog.index'), route('participant.catalog.show', $activity),
            route('participant.my-courses.index'), route('participant.learning.show', $enrollment),
        ] as $url) {
            $this->actingAs($participant)->get($url)->assertOk()
                ->assertSee('Curso de prueba')->assertDontSee('Edición de catálogo');
        }

        $this->assertSame('Curso de prueba - Edición de catálogo', $activity->fresh()->name);
    }
}
