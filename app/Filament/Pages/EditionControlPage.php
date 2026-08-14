<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\TrainingManagement\TrainingManagementCluster;
use App\Filament\Resources\ActivityResource;
use App\Filament\Resources\AttendanceRecordResource;
use App\Filament\Resources\CertificateResource;
use App\Filament\Resources\DigitalResourceResource;
use App\Filament\Resources\EnrollmentResource;
use App\Filament\Resources\EvaluationResource;
use App\Filament\Resources\EvidenceResource;
use App\Models\Activity;
use App\Models\AttendanceRecord;
use App\Models\DigitalResource;
use Filament\Actions\Action;
use Filament\Panel;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Gate;

class EditionControlPage extends Page
{
    protected static ?string $cluster = TrainingManagementCluster::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'actividad';

    protected string $view = 'filament.pages.edition-control';

    /** @var Activity */
    public $record;

    public function mount(int|string $record): void
    {
        $routeColumn = ctype_digit((string) $record)
            ? (new Activity)->getKeyName()
            : (new Activity)->getRouteKeyName();

        $this->record = Activity::query()
            ->visibleTo(auth()->user())
            ->with(['activityType', 'area', 'instructor'])
            ->where($routeColumn, $record)
            ->firstOrFail();

        Gate::authorize('view', $this->record);
    }

    public static function getRoutePath(Panel $panel): string
    {
        return '/actividad/{record}';
    }

    public function getHeading(): string
    {
        return $this->record->name;
    }

    public function getSubheading(): ?string
    {
        return collect([
            $this->record->activityType?->name,
            $this->record->status,
        ])->filter()->implode(' · ');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('volver_actividades')
                ->label('Volver a cursos y actividades')
                ->icon(Heroicon::OutlinedArrowLeft)
                ->color('gray')
                ->url(ActivityResource::getUrl()),
        ];
    }

    protected function getViewData(): array
    {
        return [
            'edition' => $this->record,
            'cards' => [
                [
                    'title' => 'Inscripciones',
                    'description' => 'Altas, seguimiento y estatus de participación.',
                    'count' => $this->record->enrollments()->count(),
                    'url' => EnrollmentResource::getUrl(parameters: ['activity' => $this->record->id]),
                    'icon' => Heroicon::OutlinedUsers,
                ],
                [
                    'title' => 'Asistencia',
                    'description' => 'Control de sesiones y asistencia registrada.',
                    'count' => AttendanceRecord::query()->where('activity_id', $this->record->id)->count(),
                    'url' => AttendanceRecordResource::getUrl(parameters: ['activity' => $this->record->id]),
                    'icon' => Heroicon::OutlinedClipboardDocumentList,
                ],
                [
                    'title' => 'Evidencias',
                    'description' => 'Entrega, revisión y validación de evidencias.',
                    'count' => $this->record->evidences()->count(),
                    'url' => EvidenceResource::getUrl(parameters: ['activity' => $this->record->id]),
                    'icon' => Heroicon::OutlinedDocumentText,
                ],
                [
                    'title' => 'Evaluaciones',
                    'description' => 'Instrumentos y evaluaciones asignadas a la actividad.',
                    'count' => $this->record->evaluations()->count(),
                    'url' => EvaluationResource::getUrl(parameters: ['activity' => $this->record->id]),
                    'icon' => Heroicon::OutlinedAcademicCap,
                ],
                [
                    'title' => 'Archivos',
                    'description' => 'Materiales y recursos vinculados a la actividad.',
                    'count' => DigitalResource::query()->where('activity_id', $this->record->id)->count(),
                    'url' => DigitalResourceResource::getUrl(parameters: ['activity' => $this->record->id]),
                    'icon' => Heroicon::OutlinedFolderOpen,
                ],
                [
                    'title' => 'Constancias',
                    'description' => 'Emisión y seguimiento de constancias de la actividad.',
                    'count' => $this->record->certificates()->count(),
                    'url' => CertificateResource::getUrl(parameters: ['activity' => $this->record->id]),
                    'icon' => Heroicon::OutlinedIdentification,
                ],
            ],
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            TrainingManagementCluster::getUrl() => TrainingManagementCluster::getClusterBreadcrumb(),
            ActivityResource::getUrl() => 'Cursos y actividades',
            $this->getHeading(),
        ];
    }
}
