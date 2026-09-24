<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\LearningPathManagement\LearningPathManagementCluster;
use App\Filament\Resources\LearningPathResource;
use App\Models\LearningPath;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Gate;

class LearningPathControlPage extends Page
{
    protected static ?string $cluster = LearningPathManagementCluster::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'ruta';

    protected string $view = 'filament.pages.learning-path-control';

    /** @var LearningPath */
    public $record;

    public function mount(int|string $record): void
    {
        $routeColumn = ctype_digit((string) $record)
            ? (new LearningPath)->getKeyName()
            : (new LearningPath)->getRouteKeyName();

        $this->record = LearningPath::query()
            ->visibleTo(auth()->user())
            ->with(['area'])
            ->withCount(['competencyDefinitions', 'trainingPrograms', 'userLearningPaths'])
            ->where($routeColumn, $record)
            ->firstOrFail();

        Gate::authorize('view', $this->record);
    }

    public static function getRoutePath(Panel $panel): string
    {
        return '/ruta/{record}';
    }

    public function getHeading(): ?string
    {
        return null;
    }

    public function getSubheading(): ?string
    {
        return null;
    }

    public function getSubNavigation(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getViewData(): array
    {
        $typeLabel = match ($this->record->path_type) {
            'certificacion' => 'Certificación',
            'diplomado' => 'Diplomado',
            'microcredencial' => 'Microcredencial',
            default => 'Ruta de aprendizaje',
        };

        return [
            'learningPath' => $this->record,
            'typeLabel' => $typeLabel,
            'pathsUrl' => LearningPathResource::getUrl(),
            'editUrl' => LearningPathResource::getUrl('edit', [
                'record' => $this->record,
            ]),
            'breadcrumbs' => [
                [
                    'label' => 'Rutas de aprendizaje',
                    'url' => LearningPathResource::getUrl(),
                ],
                [
                    'label' => $typeLabel,
                    'url' => LearningPathResource::getUrl(parameters: [
                        'tableFilters' => [
                            'path_type' => ['value' => $this->record->path_type],
                        ],
                    ]),
                ],
                [
                    'label' => $this->record->name,
                    'url' => null,
                ],
            ],
            'cards' => [
                [
                    'title' => 'Competencias',
                    'description' => 'Crea nuevas competencias o vincula las que ya existen.',
                    'count' => $this->record->competency_definitions_count,
                    'url' => LearningPathResource::getUrl('edit', [
                        'record' => $this->record,
                        'relation' => '0',
                    ]),
                    'icon' => Heroicon::OutlinedLightBulb,
                ],
                [
                    'title' => 'Programas de formación',
                    'description' => 'Agrega cursos, minicursos o talleres y define su orden.',
                    'count' => $this->record->training_programs_count,
                    'url' => LearningPathResource::getUrl('edit', [
                        'record' => $this->record,
                        'relation' => '1',
                    ]),
                    'icon' => Heroicon::OutlinedAcademicCap,
                ],
                [
                    'title' => 'Participantes',
                    'description' => 'Asigna personas y consulta su avance dentro de la ruta.',
                    'count' => $this->record->user_learning_paths_count,
                    'url' => LearningPathResource::getUrl('edit', [
                        'record' => $this->record,
                        'relation' => '2',
                    ]),
                    'icon' => Heroicon::OutlinedUsers,
                ],
            ],
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
