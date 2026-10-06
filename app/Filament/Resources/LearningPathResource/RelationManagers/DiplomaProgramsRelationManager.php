<?php

namespace App\Filament\Resources\LearningPathResource\RelationManagers;

use App\Filament\Resources\DiplomaProgramResource;
use App\Services\LearningPaths\LearningPathProgressService;
use Filament\Actions\Action;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class DiplomaProgramsRelationManager extends RelationManager
{
    protected static string $relationship = 'diplomaPrograms';

    protected static ?string $title = 'Diplomados';

    protected static string|\BackedEnum|null $icon = Heroicon::OutlinedAcademicCap;

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')->label('Diplomado')->searchable()->weight('semibold'),
                TextColumn::make('pivot.order_number')->label('Orden'),
                TextColumn::make('pivot.is_required')->label('Aplicación')->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Obligatorio' : 'Opcional'),
                TextColumn::make('generates_microcredential')->label('Microcredencial')->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Sí' : 'No'),
            ])
            ->headerActions([
                AttachAction::make()->label('Agregar diplomado')->preloadRecordSelect()->attachAnother(false)
                    ->schema(fn (AttachAction $action): array => [
                        $action->getRecordSelect()->label('Diplomado'),
                        TextInput::make('order_number')->label('Orden')->numeric()->minValue(1)->default(1)->required(),
                        Select::make('is_required')->label('Aplicación')->options([1 => 'Obligatorio', 0 => 'Opcional'])->default(1)->required(),
                    ])->after(fn () => $this->synchronizeAssignments()),
            ])
            ->recordActions([
                Action::make('abrir')->label('Gestionar diplomado')->url(fn (Model $record): string => DiplomaProgramResource::getUrl('edit', ['record' => $record])),
                Action::make('configurar')->label('Configurar')->fillForm(fn (Model $record): array => [
                    'order_number' => $record->pivot->order_number,
                    'is_required' => (int) $record->pivot->is_required,
                ])->schema([
                    TextInput::make('order_number')->label('Orden')->numeric()->minValue(1)->required(),
                    Select::make('is_required')->label('Aplicación')->options([1 => 'Obligatorio', 0 => 'Opcional'])->required(),
                ])->action(function (Model $record, array $data): void {
                    $this->getOwnerRecord()->diplomaPrograms()->updateExistingPivot($record->getKey(), $data);
                    $this->synchronizeAssignments();
                }),
                DetachAction::make()->label('Quitar de la ruta')->after(fn () => $this->synchronizeAssignments()),
            ])
            ->emptyStateHeading('Sin diplomados vinculados')
            ->emptyStateDescription('Crea un diplomado en este apartado o vincula uno existente.');
    }

    private function synchronizeAssignments(): void
    {
        $this->getOwnerRecord()->unsetRelations()->userLearningPaths()
            ->each(fn ($assignment) => app(LearningPathProgressService::class)->synchronizeAssignment($assignment));
    }
}
