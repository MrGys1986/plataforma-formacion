<?php

namespace App\Filament\Resources\LearningPathResource\RelationManagers;

use App\Services\LearningPaths\LearningPathProgressService;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LegacyActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Actividades anteriores';

    protected static string|\BackedEnum|null $icon = Heroicon::OutlinedClock;

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('activity.name')
            ->columns([
                TextColumn::make('activity.name')->label('Actividad')->searchable(),
                TextColumn::make('order_number')->label('Orden')->sortable(),
                TextColumn::make('is_required')->label('Aplicación')->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Obligatoria' : 'Opcional'),
                TextColumn::make('minimum_score')->label('Mínimo')->placeholder('Sin mínimo'),
            ])
            ->recordActions([
                EditAction::make()->schema([
                    TextInput::make('order_number')->label('Orden')->numeric()->minValue(1)->required(),
                    Select::make('is_required')->label('Aplicación')
                        ->options([1 => 'Obligatoria', 0 => 'Opcional'])->required(),
                    TextInput::make('minimum_score')->label('Calificación mínima')
                        ->numeric()->minValue(0)->maxValue(100),
                ]),
                DeleteAction::make()->label('Quitar de la ruta')
                    ->after(fn () => $this->getOwnerRecord()->userLearningPaths()
                        ->each(fn ($assignment) => app(LearningPathProgressService::class)->synchronizeAssignment($assignment))),
            ])
            ->emptyStateHeading('No hay actividades anteriores')
            ->emptyStateDescription('Las rutas nuevas se construyen con programas reutilizables.');
    }
}
