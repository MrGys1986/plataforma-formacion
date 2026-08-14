<?php

namespace App\Filament\Widgets;

use App\Models\Activity;
use App\Models\Enrollment;
use App\Models\Evidence;
use App\Models\TrainingProgram;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PlatformOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Panorama de la plataforma';

    protected ?string $description = 'Indicadores operativos para priorizar la gestión diaria.';

    public static function canView(): bool
    {
        return auth()->user()?->hasAnyRole([
            'Superadministrador',
            'Recursos Humanos',
            'Calidad Academica',
            'Educacion Continua',
            'Responsable Area',
        ]) ?? false;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Usuarios activos', User::query()->where('status', 'activo')->count())
                ->description('Cuentas habilitadas'),
            Stat::make('Programas formativos', TrainingProgram::query()->where('status', 'activo')->count())
                ->description('Cursos, minicursos y talleres'),
            Stat::make('Actividades en operación', Activity::query()->whereIn('status', ['en_inscripcion', 'publicado', 'en_curso'])->count())
                ->description('Abiertas o en curso'),
            Stat::make('Inscripciones pendientes', Enrollment::query()->where('status', 'solicitada')->count())
                ->description('Requieren atención'),
            Stat::make('Evidencias pendientes', Evidence::query()->where('status', 'pendiente')->count())
                ->description('Requieren revisión'),
        ];
    }
}
