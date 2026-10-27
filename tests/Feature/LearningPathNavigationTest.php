<?php

namespace Tests\Feature;

use App\Filament\Pages\LearningPathControlPage;
use App\Filament\Resources\LearningPathResource;
use App\Filament\Resources\LearningPathResource\Pages\EditLearningPath;
use App\Filament\Resources\LearningPathResource\Pages\ManageLearningPaths;
use App\Models\Area;
use App\Models\LearningPath;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LearningPathNavigationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::findOrCreate('Superadministrador');
        $administrator = User::factory()->create(['status' => 'activo']);
        $administrator->assignRole('Superadministrador');
        $this->actingAs($administrator);
    }

    public function test_deleted_paths_do_not_appear_in_the_management_list(): void
    {
        $active = $this->path('Ruta disponible');
        $deleted = $this->path('Ruta eliminada');
        $deleted->delete();

        Livewire::test(ManageLearningPaths::class)
            ->assertCanSeeTableRecords([$active])
            ->assertCanNotSeeTableRecords([$deleted]);
    }

    public function test_returning_to_a_deleted_path_redirects_to_the_list_without_restoring_it(): void
    {
        $path = $this->path('Ruta eliminada');
        $path->delete();

        foreach ([$path->id, $path->public_id] as $record) {
            $this->get(LearningPathControlPage::getUrl(['record' => $record]))
                ->assertRedirect(LearningPathResource::getUrl());
        }

        $this->assertSoftDeleted($path);
    }

    public function test_returning_from_editing_an_active_path_does_not_change_it(): void
    {
        $path = $this->path('Ruta disponible');
        $original = $path->fresh()->getAttributes();

        $page = Livewire::test(EditLearningPath::class, ['record' => $path->public_id]);
        $backUrl = $page->instance()->getAction('volver_control')->getUrl();
        $this->get($backUrl)->assertOk()->assertSee('Ruta disponible');

        $this->assertSame($original, $path->fresh()->getAttributes());
    }

    public function test_an_unknown_path_still_returns_not_found(): void
    {
        $this->get(LearningPathControlPage::getUrl(['record' => 999999]))->assertNotFound();
    }

    public function test_deleted_paths_from_another_area_are_not_exposed(): void
    {
        $area = Area::create(['name' => 'Otra área', 'area_type' => 'academica', 'status' => 'activa']);
        $path = $this->path('Ruta de otra área');
        $path->update(['area_id' => $area->id]);
        $path->delete();
        Role::findOrCreate('Responsable Area');
        $manager = User::factory()->create(['status' => 'activo', 'area_id' => null]);
        $manager->assignRole('Responsable Area');

        $this->actingAs($manager)->get(LearningPathControlPage::getUrl(['record' => $path->id]))
            ->assertNotFound();
    }

    private function path(string $name): LearningPath
    {
        return LearningPath::create([
            'name' => $name, 'slug' => str($name)->slug()->toString(), 'status' => 'publicada',
        ]);
    }
}
