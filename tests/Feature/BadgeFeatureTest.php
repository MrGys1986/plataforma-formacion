<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Enrollment;
use App\Models\LearningPath;
use App\Models\Microcredential;
use App\Models\User;
use App\Models\UserLearningPath;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class BadgeFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_professor_can_view_their_badge_and_its_public_verification(): void
    {
        Role::findOrCreate('Profesor');
        $professor = User::factory()->create(['status' => 'activo']);
        $professor->assignRole('Profesor');

        $path = LearningPath::create([
            'name' => 'Ruta de docencia innovadora',
            'slug' => 'ruta-docencia-innovadora',
            'status' => 'publicada',
        ]);

        $badge = Microcredential::create([
            'user_id' => $professor->id,
            'learning_path_id' => $path->id,
            'name' => 'Docencia innovadora',
            'description' => 'Insignia de prueba.',
            'status' => 'validada',
            'issued_at' => now(),
        ]);

        $this->actingAs($professor)
            ->get(route('participant.badges.index'))
            ->assertOk()
            ->assertSee('Mis insignias')
            ->assertSee('Docencia innovadora');

        $this->actingAs($professor)
            ->get(route('participant.badges.show', $badge))
            ->assertOk()
            ->assertSee('Verificada')
            ->assertSee($badge->public_id);

        $this->get(route('public.badges.verify', $badge))
            ->assertOk()
            ->assertSee('Insignia válida y verificada')
            ->assertSee($professor->name);
    }

    public function test_one_badge_is_issued_when_a_learning_path_is_completed(): void
    {
        $participant = User::factory()->create(['status' => 'activo']);
        $type = ActivityType::create(['name' => 'Curso', 'status' => 'activo']);
        $firstActivity = Activity::create([
            'activity_type_id' => $type->id,
            'name' => 'Inducción institucional',
            'slug' => 'induccion-institucional-ruta',
            'duration_hours' => 4,
            'status' => 'publicado',
        ]);
        $secondActivity = Activity::create([
            'activity_type_id' => $type->id,
            'name' => 'Cultura institucional',
            'slug' => 'cultura-institucional-ruta',
            'duration_hours' => 4,
            'status' => 'publicado',
        ]);
        $path = LearningPath::create([
            'name' => 'Ruta de inducción',
            'slug' => 'ruta-de-induccion',
            'status' => 'publicada',
        ]);
        foreach ([$firstActivity, $secondActivity] as $index => $activity) {
            $path->items()->create([
                'activity_id' => $activity->id,
                'order_number' => $index + 1,
                'is_required' => true,
            ]);
        }

        UserLearningPath::create(['user_id' => $participant->id, 'learning_path_id' => $path->id]);
        $enrollments = $participant->enrollments()->orderBy('activity_id')->get();

        $this->assertDatabaseCount('microcredentials', 0);

        $enrollments->first()->update(['completion_status' => 'completado']);
        $this->assertDatabaseCount('microcredentials', 0);

        $enrollments->last()->update(['completion_status' => 'completado']);

        $this->assertDatabaseHas('microcredentials', [
            'user_id' => $participant->id,
            'learning_path_id' => $path->id,
            'activity_id' => null,
            'name' => 'Insignia de Ruta de inducción',
            'status' => 'validada',
        ]);

        $enrollments->last()->update(['final_score' => 95]);

        $this->assertDatabaseCount('microcredentials', 1);
    }
}
