<?php

namespace Tests\Feature;

use App\Filament\Resources\LearningPathResource;
use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\LearningPath;
use App\Models\User;
use App\Models\UserLearningPath;
use App\Services\LearningPaths\LearningPathProgressService;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
            ->assertSee('Rutas de aprendizaje');
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
