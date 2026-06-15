<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\AuditManagement\AuditManagementCluster;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class ApplicationLogs extends Page
{
    protected static ?int $navigationSort = 2;

    protected static ?string $cluster = AuditManagementCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Registro de aplicación';

    protected static ?string $title = 'Registro de aplicación';

    protected static string $routePath = 'registro-aplicacion';

    protected string $view = 'filament.pages.application-logs';

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['Superadministrador', 'Calidad Academica']) ?? false;
    }

    protected function getViewData(): array
    {
        $files = glob(storage_path('logs/*.log')) ?: [];
        usort($files, fn (string $left, string $right): int => filemtime($right) <=> filemtime($left));
        $file = $files[0] ?? null;

        return [
            'fileName' => $file ? basename($file) : null,
            'contents' => $file ? $this->tail($file) : 'No hay archivos de registro disponibles.',
        ];
    }

    private function tail(string $path, int $bytes = 150000): string
    {
        $handle = fopen($path, 'rb');

        if (! $handle) {
            return 'No fue posible abrir el archivo de registro.';
        }

        $size = filesize($path) ?: 0;
        fseek($handle, max(0, $size - $bytes));
        $contents = stream_get_contents($handle) ?: '';
        fclose($handle);

        return $contents;
    }
}
