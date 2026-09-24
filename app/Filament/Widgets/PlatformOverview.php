<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ActivityResource;
use App\Filament\Resources\EnrollmentResource;
use App\Filament\Resources\EvidenceResource;
use App\Filament\Resources\TrainingProgramResource;
use App\Filament\Resources\UserResource;
use App\Models\Activity;
use App\Models\Enrollment;
use App\Models\Evidence;
use App\Models\TrainingProgram;
use App\Models\User;
use Filament\Schemas\Components\Component;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PlatformOverview extends StatsOverviewWidget
{
    protected int|array|null $columns = [
        '@md' => 2,
        '@xl' => 5,
    ];

    protected ?string $heading = 'Panorama de la plataforma';

    protected ?string $description = 'Una lectura rápida del estado actual de la operación institucional.';

    public function getSectionContentComponent(): Component
    {
        return parent::getSectionContentComponent()
            ->icon(Heroicon::OutlinedChartBarSquare)
            ->iconColor('primary')
            ->extraAttributes(['class' => 'pf-dashboard-overview']);
    }

    public static function canView(): bool
    {
        return auth()->user()?->hasAnyRole(User::FILAMENT_ROLES) ?? false;
    }

    protected function getStats(): array
    {
        $user = auth()->user();
        $stats = [];

        if (UserResource::canAccess()) {
            $stats[] = Stat::make('Usuarios activos', User::query()->visibleTo($user)->where('status', 'activo')->count())
                ->icon(Heroicon::OutlinedUsers)
                ->description('Cuentas habilitadas');
        }

        if (TrainingProgramResource::canAccess()) {
            $stats[] = Stat::make('Programas formativos', TrainingProgram::query()->visibleTo($user)->where('status', 'activo')->count())
                ->icon(Heroicon::OutlinedRectangleStack)
                ->description('Cursos, minicursos y talleres');
        }

        if (ActivityResource::canAccess()) {
            $stats[] = Stat::make('Actividades en operación', Activity::query()->visibleTo($user)->whereIn('status', ['en_inscripcion', 'publicado', 'en_curso'])->count())
                ->icon(Heroicon::OutlinedAcademicCap)
                ->description('Abiertas o en curso');
        }

        if (EnrollmentResource::canAccess()) {
            $stats[] = Stat::make('Inscripciones pendientes', Enrollment::query()->visibleTo($user)->where('status', 'solicitada')->count())
                ->icon(Heroicon::OutlinedClipboardDocumentCheck)
                ->description('Requieren atención');
        }

        if (EvidenceResource::canAccess()) {
            $stats[] = Stat::make('Evidencias pendientes', Evidence::query()->visibleTo($user)->where('status', 'pendiente')->count())
                ->icon(Heroicon::OutlinedDocumentCheck)
                ->description('Requieren revisión');
        }

        return $stats;
    }
}
