<?php

namespace App\Filament\Resources\LearningPathResource\Pages;

use App\Filament\Resources\LearningPathResource;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class ManageLearningPaths extends ManageRecords
{
    protected static string $resource = LearningPathResource::class;

    public function getHeading(): string|Htmlable|null
    {
        return view('filament.partials.resource-heading', [
            'icon' => Heroicon::OutlinedMap,
            'label' => 'Rutas de aprendizaje',
        ]);
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Administra certificaciones, diplomados y microcredenciales desde un solo catálogo.';
    }

    public function getTabs(): array
    {
        return [
            'todas' => Tab::make('Todas las rutas')
                ->icon(Heroicon::OutlinedSquares2x2),
            'certificaciones' => Tab::make('Certificaciones')
                ->icon(Heroicon::OutlinedCheckBadge)
                ->modifyQueryUsing(fn (Builder $query): Builder => $query
                    ->where('path_type', 'certificacion')),
            'diplomados' => Tab::make('Diplomados')
                ->icon(Heroicon::OutlinedAcademicCap)
                ->modifyQueryUsing(fn (Builder $query): Builder => $query
                    ->where('path_type', 'diplomado')),
            'microcredenciales' => Tab::make('Microcredenciales')
                ->icon(Heroicon::OutlinedSparkles)
                ->modifyQueryUsing(fn (Builder $query): Builder => $query
                    ->where('path_type', 'microcredencial')),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
