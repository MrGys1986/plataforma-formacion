<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\ReportManagement\ReportManagementCluster;
use App\Models\Activity;
use App\Models\Enrollment;
use App\Models\Evidence;
use App\Models\Microcredential;
use App\Models\Payment;
use App\Models\SurveyResponse;
use App\Models\TrainingProgram;
use App\Models\User;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class InstitutionalReports extends Page
{
    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = ReportManagementCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBarSquare;

    protected static ?string $navigationLabel = 'Resumen institucional';

    protected static ?string $title = 'Resumen institucional';

    protected static string $routePath = 'resumen';

    protected string $view = 'filament.pages.institutional-reports';

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole([
            'Superadministrador',
            'Recursos Humanos',
            'Calidad Academica',
            'Educacion Continua',
            'Responsable Area',
        ]) ?? false;
    }

    protected function getViewData(): array
    {
        return [
            'metrics' => [
                'Usuarios activos' => User::query()->where('status', 'activo')->count(),
                'Programas formativos' => TrainingProgram::query()->count(),
                'Ediciones abiertas' => Activity::query()->whereIn('status', ['en_inscripcion', 'publicado', 'en_curso'])->count(),
                'Inscripciones' => Enrollment::query()->count(),
                'Formaciones completadas' => Enrollment::query()->where('completion_status', 'completado')->count(),
                'Evidencias pendientes' => Evidence::query()->where('status', 'pendiente')->count(),
                'Respuestas de encuestas' => SurveyResponse::query()->count(),
                'Microcredenciales emitidas' => Microcredential::query()->where('status', 'validada')->count(),
            ],
            'validatedPayments' => Payment::query()->where('status', 'validado')->sum('amount'),
            'completionByProgram' => TrainingProgram::query()
                ->with('activityType')
                ->withCount([
                    'editions',
                    'enrollments as completed_enrollments_count' => fn ($query) => $query
                        ->where('completion_status', 'completado'),
                ])
                ->orderByDesc('completed_enrollments_count')
                ->limit(10)
                ->get(),
        ];
    }
}
