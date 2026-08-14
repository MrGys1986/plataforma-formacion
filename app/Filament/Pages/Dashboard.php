<?php

namespace App\Filament\Pages;

use App\Filament\Resources\AreaResource;
use App\Filament\Resources\ActivityResource;
use App\Filament\Resources\EvidenceResource;
use App\Filament\Resources\MicrocredentialResource;
use App\Filament\Resources\PaymentResource;
use App\Filament\Resources\SurveyResource;
use App\Filament\Resources\TrainingProgramResource;
use App\Filament\Resources\UserResource;
use App\Models\Activity;
use App\Models\Enrollment;
use App\Models\Evidence;
use App\Models\Microcredential;
use App\Models\Payment;
use App\Models\Survey;
use App\Models\TrainingProgram;
use App\Models\User;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;

class Dashboard extends BaseDashboard
{
    protected static string $routePath = '/';

    protected static ?string $title = 'Dashboard';

    protected Width|string|null $maxContentWidth = Width::Full;

    protected string $view = 'filament.pages.dashboard';

    protected ?string $subheading = 'Un tablero central para movernos más rápido entre operación, seguimiento y control.';

    protected function getViewData(): array
    {
        return [
            'heroMetrics' => [
                [
                    'label' => 'Usuarios activos',
                    'value' => User::query()->where('status', 'activo')->count(),
                ],
                [
                    'label' => 'Programas activos',
                    'value' => TrainingProgram::query()->where('status', 'activo')->count(),
                ],
                [
                    'label' => 'Actividades en operación',
                    'value' => Activity::query()->whereIn('status', ['publicado', 'en_inscripcion', 'en_curso'])->count(),
                ],
                [
                    'label' => 'Inscripciones pendientes',
                    'value' => Enrollment::query()->where('status', 'solicitada')->count(),
                ],
            ],
            'quickLinks' => [
                [
                    'title' => 'Usuarios',
                    'description' => 'Consulta usuarios por tipo y mantén áreas ordenadas.',
                    'url' => UserResource::getUrl(),
                    'icon' => Heroicon::OutlinedUsers,
                ],
                [
                    'title' => 'Áreas',
                    'description' => 'Revisa responsables y estructura institucional.',
                    'url' => AreaResource::getUrl(),
                    'icon' => Heroicon::OutlinedBuildingOffice,
                ],
                [
                    'title' => 'Cursos',
                    'description' => 'Administra directamente cursos, fechas e inscripciones.',
                    'url' => ActivityResource::getUrl(parameters: ['activeTab' => 'cursos']),
                    'icon' => Heroicon::OutlinedAcademicCap,
                ],
                [
                    'title' => 'Minicursos',
                    'description' => 'Separa la operación corta sin mezclarla con otros formatos.',
                    'url' => ActivityResource::getUrl(parameters: ['activeTab' => 'minicursos']),
                    'icon' => Heroicon::OutlinedBookOpen,
                ],
                [
                    'title' => 'Talleres',
                    'description' => 'Da seguimiento directo a la oferta práctica.',
                    'url' => ActivityResource::getUrl(parameters: ['activeTab' => 'talleres']),
                    'icon' => Heroicon::OutlinedWrenchScrewdriver,
                ],
                [
                    'title' => 'Pagos',
                    'description' => 'Mantén validaciones y comprobantes al día.',
                    'url' => PaymentResource::getUrl(),
                    'icon' => Heroicon::OutlinedBanknotes,
                ],
            ],
            'attentionItems' => [
                [
                    'label' => 'Evidencias pendientes de revisión',
                    'count' => Evidence::query()->where('status', 'pendiente')->count(),
                    'url' => EvidenceResource::getUrl(),
                ],
                [
                    'label' => 'Pagos registrados',
                    'count' => Payment::query()->count(),
                    'url' => PaymentResource::getUrl(),
                ],
                [
                    'label' => 'Encuestas activas',
                    'count' => Survey::query()->where('status', 'activa')->count(),
                    'url' => SurveyResource::getUrl(),
                ],
                [
                    'label' => 'Microcredenciales emitidas',
                    'count' => Microcredential::query()->where('status', 'validada')->count(),
                    'url' => MicrocredentialResource::getUrl(),
                ],
            ],
        ];
    }
}
