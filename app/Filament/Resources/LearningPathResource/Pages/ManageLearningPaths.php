<?php

namespace App\Filament\Resources\LearningPathResource\Pages;

use App\Filament\Resources\LearningPathResource;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

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
        return 'Construye rutas con diplomados, certificaciones, cursos, minicursos y talleres.';
    }

    public function getTabs(): array
    {
        return [
            'todas' => Tab::make('Todas las rutas')->icon(Heroicon::OutlinedSquares2x2),
            'publicadas' => Tab::make('Publicadas')
                ->icon(Heroicon::OutlinedCheckBadge)
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', 'publicada')),
            'borradores' => Tab::make('Borradores')
                ->icon(Heroicon::OutlinedPencilSquare)
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', 'borrador')),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = filled($data['slug'] ?? null)
            ? Str::slug($data['slug'])
            : Str::slug((string) ($data['name'] ?? 'ruta-de-aprendizaje'));

        return $data;
    }
}
