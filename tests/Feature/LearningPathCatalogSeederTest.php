<?php

namespace Tests\Feature;

use App\Models\LearningPath;
use App\Models\Activity;
use App\Models\Competency;
use App\Models\User;
use App\Services\LearningPaths\LearningPathProgressService;
use App\Services\Learning\LearningProgressService;
use App\Services\Microcredentials\LearningPathBadgeService;
use Database\Seeders\LearningPathCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LearningPathCatalogSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_has_distinct_hierarchies_without_assigning_users(): void
    {
        $this->seed(LearningPathCatalogSeeder::class);

        $this->assertDatabaseCount('learning_paths', 4);
        $this->assertDatabaseCount('diploma_programs', 3);
        $this->assertDatabaseCount('certification_programs', 1);
        $this->assertDatabaseCount('training_programs', 12);
        $this->assertDatabaseCount('activities', 12);
        $this->assertDatabaseCount('user_learning_paths', 0);
        $this->assertDatabaseCount('enrollments', 0);
        $this->assertDatabaseCount('users', 0);
        $this->assertSame(4, DB::table('learning_paths')->whereNotNull('competencies')->count());
        $this->assertSame(3, DB::table('diploma_programs')->whereNotNull('competencies')->count());
        $this->assertSame(1, DB::table('certification_programs')->whereNotNull('competencies')->count());
        $this->assertSame(12, DB::table('training_programs')->whereNotNull('competencies')->count());

        $service = app(LearningPathProgressService::class);
        $simple = LearningPath::where('slug', 'ruta-seguridad-operacional-basica-catalogo')->firstOrFail();
        $nested = LearningPath::where('slug', 'ruta-calidad-y-seguridad-integral-catalogo')->firstOrFail();

        $this->assertCount(3, $service->itemsFor($simple));
        $nestedItems = $service->itemsFor($nested);
        $this->assertCount(6, $nestedItems);
        $this->assertTrue($nestedItems->every(fn (object $item): bool => $item->activity_id !== null));
        $this->assertTrue($nestedItems->contains(fn (object $item): bool => str_contains($item->group_name, 'Certificación en calidad y seguridad operacional / Diplomado')));
        $this->assertSame(1, $nested->certificationPrograms()->count());
        $this->assertSame(1, $nested->certificationPrograms()->first()->diplomaPrograms()->count());
        $this->assertTrue($simple->generates_microcredential);
        $this->assertTrue($nested->certificationPrograms()->first()->generates_microcredential);

        $this->seed(LearningPathCatalogSeeder::class);

        $this->assertDatabaseCount('learning_paths', 4);
        $this->assertDatabaseCount('diploma_programs', 3);
        $this->assertDatabaseCount('certification_programs', 1);
        $this->assertDatabaseCount('training_programs', 12);
        $this->assertDatabaseCount('activities', 12);
        $this->assertSame(3, DB::table('learning_path_training_program')->where('learning_path_id', $simple->id)->count());
        $this->assertDatabaseCount('user_learning_paths', 0);
        $this->assertDatabaseCount('enrollments', 0);
    }

    public function test_competencies_are_informational_and_are_copied_to_issued_badges(): void
    {
        $this->seed(LearningPathCatalogSeeder::class);

        $user = User::factory()->create();
        Competency::create(['name' => 'Registro legado', 'slug' => 'registro-legado', 'status' => 'activo']);
        app(LearningProgressService::class)->recalculate($user);
        $this->assertDatabaseCount('user_competencies', 0);

        $activity = Activity::whereHas('trainingProgram', fn ($query) => $query
            ->where('slug', 'cultura-de-reporte-aeronautico-catalogo'))->firstOrFail();
        $this->assertNull($activity->competencies);
        $badge = app(LearningPathBadgeService::class)->issue($user, $activity);

        $this->assertSame($activity->trainingProgram->competencies, $badge->competencies);
        $this->assertSame($badge->competencies, $badge->json_payload['competencies']);

        $badge->update(['competencies' => 'Nueva competencia validada.']);
        $this->assertSame('Nueva competencia validada.', $badge->refresh()->json_payload['competencies']);
    }

    public function test_participant_can_read_competencies_at_each_level(): void
    {
        $this->seed(LearningPathCatalogSeeder::class);
        Role::findOrCreate('Alumno');
        $user = User::factory()->create();
        $user->assignRole('Alumno');

        $path = LearningPath::where('slug', 'ruta-calidad-y-seguridad-integral-catalogo')->firstOrFail();
        $this->actingAs($user)
            ->get(route('participant.learning-paths.show', $path))
            ->assertOk()
            ->assertSee($path->competencies)
            ->assertSee('Integra seguridad operacional, auditoría y mejora de procesos.')
            ->assertSee('Gestiona peligros y riesgos para fortalecer la seguridad operacional.');

        $activity = Activity::whereHas('trainingProgram', fn ($query) => $query
            ->where('slug', 'sistemas-de-gestion-de-calidad-catalogo'))->firstOrFail();
        $this->actingAs($user)
            ->get(route('participant.catalog.show', $activity))
            ->assertOk()
            ->assertSee($activity->trainingProgram->competencies);
    }
}
