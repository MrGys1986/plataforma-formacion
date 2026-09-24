<?php

namespace App\Filament\Pages;

use App\Filament\Resources\AreaResource;
use App\Filament\Resources\EnrollmentResource;
use App\Filament\Resources\EvidenceResource;
use App\Filament\Resources\MicrocredentialResource;
use App\Filament\Resources\PaymentResource;
use App\Filament\Resources\TrainingProgramResource;
use App\Filament\Resources\UserResource;
use App\Models\Evidence;
use App\Models\Microcredential;
use App\Models\Payment;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class Dashboard extends BaseDashboard
{
    protected static string $routePath = '/';

    protected static ?string $title = 'Dashboard';

    protected Width|string|null $maxContentWidth = Width::Full;

    protected string $view = 'filament.pages.dashboard';

    public function getHeading(): string|Htmlable|null
    {
        return null;
    }

    public function getPageClasses(): array
    {
        return ['pf-dashboard-page'];
    }

    protected function getViewData(): array
    {
        $user = auth()->user();
        $quickLinks = [];
        $attentionItems = [];

        if (UserResource::canAccess()) {
            $quickLinks[] = [
                'title' => 'Usuarios',
                'description' => 'Consulta cuentas, roles y perfiles permitidos para tu función.',
                'url' => UserResource::getUrl(),
                'icon' => Heroicon::OutlinedUsers,
            ];
        }

        if (AreaResource::canAccess()) {
            $quickLinks[] = [
                'title' => 'Áreas',
                'description' => 'Revisa responsables y estructura institucional.',
                'url' => AreaResource::getUrl(),
                'icon' => Heroicon::OutlinedBuildingOffice,
            ];
        }

        if (TrainingProgramResource::canAccess()) {
            $quickLinks[] = [
                'title' => 'Programas de formación',
                'description' => 'Administra cursos, minicursos y talleres desde un solo catálogo.',
                'url' => TrainingProgramResource::getUrl(),
                'icon' => Heroicon::OutlinedRectangleStack,
            ];
        }

        if (EnrollmentResource::canAccess()) {
            $quickLinks[] = [
                'title' => 'Inscripciones',
                'description' => 'Revisa participantes, estados y avance por actividad.',
                'url' => EnrollmentResource::getUrl(),
                'icon' => Heroicon::OutlinedClipboardDocumentCheck,
            ];
        }

        if (EvidenceResource::canAccess()) {
            $pendingEvidenceCount = Evidence::query()->visibleTo($user)->where('status', 'pendiente')->count();
            $quickLinks[] = [
                'title' => 'Evidencias',
                'description' => 'Consulta entregas y revisiones dentro de tu ámbito.',
                'url' => EvidenceResource::getUrl(),
                'icon' => Heroicon::OutlinedDocumentCheck,
            ];
            $attentionItems[] = [
                'label' => 'Evidencias pendientes de revisión',
                'count' => $pendingEvidenceCount,
                'url' => EvidenceResource::getUrl(),
            ];
        }

        if (PaymentResource::canAccess()) {
            $quickLinks[] = [
                'title' => 'Pagos',
                'description' => 'Mantén comprobantes y validaciones al día.',
                'url' => PaymentResource::getUrl(),
                'icon' => Heroicon::OutlinedBanknotes,
            ];
            $attentionItems[] = [
                'label' => 'Pagos registrados',
                'count' => Payment::query()->visibleTo($user)->count(),
                'url' => PaymentResource::getUrl(),
            ];
        }

        if (MicrocredentialResource::canAccess()) {
            $attentionItems[] = [
                'label' => 'Microcredenciales emitidas',
                'count' => Microcredential::query()->visibleTo($user)->where('status', 'validada')->count(),
                'url' => MicrocredentialResource::getUrl(),
            ];
        }

        return [
            'dashboardRole' => $user?->getRoleNames()->first() ?? 'Administración',
            'quickLinks' => $quickLinks,
            'attentionItems' => $attentionItems,
        ];
    }
}
