<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\TrainingManagement\TrainingManagementCluster;
use App\Filament\Resources\AttendanceRecordResource;
use App\Filament\Resources\CertificateResource;
use App\Filament\Resources\DigitalResourceResource;
use App\Filament\Resources\EnrollmentResource;
use App\Filament\Resources\EvaluationResource;
use App\Filament\Resources\EvidenceResource;
use App\Filament\Resources\SurveyResponseResource;
use App\Filament\Resources\TrainingProgramResource;
use App\Models\Activity;
use App\Models\AttendanceRecord;
use App\Models\Certificate;
use App\Models\DigitalResource;
use App\Models\Enrollment;
use App\Models\Evaluation;
use App\Models\Evidence;
use App\Models\SurveyResponse;
use App\Models\TrainingProgram;
use App\Services\Training\TrainingProgramOperationService;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Gate;

class ProgramControlPage extends Page
{
    protected static ?string $cluster = TrainingManagementCluster::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'programa';

    protected string $view = 'filament.pages.program-control';

    /** @var TrainingProgram */
    public $record;

    /** @var Activity */
    public $activity;

    public function mount(int|string $record): void
    {
        $routeColumn = ctype_digit((string) $record)
            ? (new TrainingProgram)->getKeyName()
            : (new TrainingProgram)->getRouteKeyName();

        $this->record = TrainingProgram::query()
            ->visibleTo(auth()->user())
            ->with(['activityType', 'area'])
            ->where($routeColumn, $record)
            ->firstOrFail();

        Gate::authorize('view', $this->record);

        $this->activity = $this->resolveOperationalActivity();
    }

    public static function getRoutePath(Panel $panel): string
    {
        return '/programa/{record}';
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
        $activityIds = $this->record->activities()->pluck('id');
        $parameters = [
            'activity' => $this->activity->id,
            'training_program' => $this->record->id,
        ];

        return [
            'program' => $this->record,
            'programsUrl' => TrainingProgramResource::getUrl(),
            'breadcrumbs' => [
                [
                    'label' => TrainingManagementCluster::getClusterBreadcrumb(),
                    'url' => TrainingManagementCluster::getUrl(),
                ],
                [
                    'label' => 'Programas de formación',
                    'url' => TrainingProgramResource::getUrl(),
                ],
                [
                    'label' => $this->record->name,
                    'url' => null,
                ],
            ],
            'cards' => [
                [
                    'title' => 'Inscripciones',
                    'description' => 'Altas, seguimiento y estado de participación.',
                    'count' => Enrollment::query()->whereIn('activity_id', $activityIds)->count(),
                    'url' => EnrollmentResource::getUrl(parameters: $parameters),
                    'icon' => Heroicon::OutlinedUsers,
                ],
                [
                    'title' => 'Asistencia',
                    'description' => 'Control de sesiones y asistencia registrada.',
                    'count' => AttendanceRecord::query()->whereIn('activity_id', $activityIds)->count(),
                    'url' => AttendanceRecordResource::getUrl(parameters: $parameters),
                    'icon' => Heroicon::OutlinedClipboardDocumentList,
                ],
                [
                    'title' => 'Evidencias',
                    'description' => 'Entrega, revisión y validación de evidencias.',
                    'count' => Evidence::query()->whereIn('activity_id', $activityIds)->count(),
                    'url' => EvidenceResource::getUrl(parameters: $parameters),
                    'icon' => Heroicon::OutlinedDocumentText,
                ],
                [
                    'title' => 'Evaluaciones',
                    'description' => 'Instrumentos y evaluaciones del programa.',
                    'count' => Evaluation::query()->whereIn('activity_id', $activityIds)->count(),
                    'url' => EvaluationResource::getUrl(parameters: $parameters),
                    'icon' => Heroicon::OutlinedAcademicCap,
                ],
                [
                    'title' => 'Encuesta de satisfacción',
                    'description' => 'Respuestas y seguimiento de satisfacción del programa.',
                    'count' => SurveyResponse::query()->whereIn('activity_id', $activityIds)->count(),
                    'url' => SurveyResponseResource::getUrl(parameters: $parameters),
                    'icon' => Heroicon::OutlinedChatBubbleLeftRight,
                ],
                [
                    'title' => 'Archivos',
                    'description' => 'Materiales y recursos vinculados al programa.',
                    'count' => DigitalResource::query()->whereIn('activity_id', $activityIds)->count(),
                    'url' => DigitalResourceResource::getUrl(parameters: $parameters),
                    'icon' => Heroicon::OutlinedFolderOpen,
                ],
                [
                    'title' => 'Constancias',
                    'description' => 'Emisión y seguimiento de constancias.',
                    'count' => Certificate::query()->whereIn('activity_id', $activityIds)->count(),
                    'url' => CertificateResource::getUrl(parameters: $parameters),
                    'icon' => Heroicon::OutlinedIdentification,
                ],
            ],
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    private function resolveOperationalActivity(): Activity
    {
        return app(TrainingProgramOperationService::class)->resolve($this->record);
    }
}
