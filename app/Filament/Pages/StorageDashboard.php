<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\ResourceManagement\ResourceManagementCluster;
use App\Models\FileUpload;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StorageDashboard extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?int $navigationSort = 20;
    protected static ?string $cluster = ResourceManagementCluster::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCircleStack;
    protected static ?string $navigationLabel = 'Almacenamiento';
    protected static ?string $title = 'Panel de almacenamiento';
    protected static ?string $slug = 'almacenamiento';
    protected string $view = 'filament.pages.storage-dashboard';

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['Superadministrador', 'Calidad Academica']) ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(FileUpload::query()->with('uploadedBy'))
            ->columns([
                TextColumn::make('original_name')
                    ->label('Archivo')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),
                TextColumn::make('disk')
                    ->label('Ubicación')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mime_type')
                    ->label('Tipo')
                    ->placeholder('Sin tipo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('uploadedBy.name')
                    ->label('Usuario')
                    ->placeholder('Sistema')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('disk')
                    ->label('Ubicación')
                    ->options(fn (): array => FileUpload::query()
                        ->whereNotNull('disk')
                        ->distinct()
                        ->orderBy('disk')
                        ->pluck('disk', 'disk')
                        ->all()),
                SelectFilter::make('mime_type')
                    ->label('Tipo de archivo')
                    ->options(fn (): array => FileUpload::query()
                        ->whereNotNull('mime_type')
                        ->distinct()
                        ->orderBy('mime_type')
                        ->pluck('mime_type', 'mime_type')
                        ->all()),
            ])
            ->deferFilters(false)
            ->defaultSort('created_at', 'desc')
            ->searchDebounce('400ms')
            ->searchPlaceholder('Buscar archivo, ubicación, tipo o usuario')
            ->paginationPageOptions([10, 25, 50, 100])
            ->persistSearchInSession()
            ->persistFiltersInSession()
            ->extraAttributes(['class' => 'pf-management-table']);
    }

    protected function getViewData(): array
    {
        $active = FileUpload::query();
        $orphans = FileUpload::query()->get()->filter->isOrphan();

        return [
            'metrics' => [
                'Archivos activos' => (clone $active)->count(),
                'Espacio registrado' => $this->formatBytes((int) (clone $active)->sum('size')),
                'En Cloudinary' => (clone $active)->where('disk', 'cloudinary')->count(),
                'En almacenamiento local' => (clone $active)->whereIn('disk', ['local', 'public'])->count(),
                'Imágenes optimizables' => (clone $active)->where('resource_type', 'image')->count(),
                'Errores pendientes' => (clone $active)->whereNotNull('last_error')->count(),
                'Archivos huérfanos' => $orphans->count(),
                'En retención' => FileUpload::onlyTrashed()->count(),
            ],
            'byType' => (clone $active)->selectRaw("COALESCE(mime_type, 'Sin tipo') as type, COUNT(*) as total, COALESCE(SUM(size), 0) as bytes")->groupBy('mime_type')->orderByDesc('total')->get(),
            'orphans' => $orphans->take(10),
        ];
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes < 1024) return $bytes.' B';
        if ($bytes < 1048576) return number_format($bytes / 1024, 1).' KB';
        if ($bytes < 1073741824) return number_format($bytes / 1048576, 1).' MB';
        return number_format($bytes / 1073741824, 2).' GB';
    }
}
