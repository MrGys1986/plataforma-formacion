<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\ReportManagement\ReportManagementCluster;
use App\Services\Reports\InstitutionalReportService;
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
        return auth()->user()?->hasAnyRole(InstitutionalReportService::OVERVIEW_ROLES) ?? false;
    }

    protected function getViewData(): array
    {
        return app(InstitutionalReportService::class)->institutionalOverview(auth()->user());
    }
}
