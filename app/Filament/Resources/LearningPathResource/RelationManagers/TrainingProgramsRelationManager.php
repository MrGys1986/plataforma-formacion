<?php

namespace App\Filament\Resources\LearningPathResource\RelationManagers;

use App\Filament\Pages\ProgramControlPage;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TrainingProgramsRelationManager extends RelationManager
{
    protected static string $relationship = 'trainingPrograms';

    protected static ?string $title = 'Programas de formación';

    protected static string|\BackedEnum|null $icon = Heroicon::OutlinedAcademicCap;

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Programa')
                    ->icon(Heroicon::OutlinedAcademicCap)
                    ->weight('semibold')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('activityType.name')
                    ->label('Tipo')
                    ->badge()
                    ->color('info'),
                TextColumn::make('duration_hours')
                    ->label('Duración')
                    ->suffix(' h')
                    ->numeric(decimalPlaces: 0),
                TextColumn::make('pivot.is_required')
                    ->label('Aplicación')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Obligatorio' : 'Opcional')
                    ->color(fn (bool $state): string => $state ? 'primary' : 'gray'),
                TextColumn::make('pivot.minimum_score')
                    ->label('Mínimo')
                    ->placeholder('Sin mínimo'),
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Agregar programa')
                    ->icon(Heroicon::OutlinedPlus)
                    ->recordSelectOptionsQuery(fn (Builder $query): Builder => $query
                        ->whereHas('activityType', fn (Builder $types): Builder => $types
                            ->whereIn('name', ['Curso', 'Minicurso', 'Taller'])))
                    ->recordSelectSearchColumns(['name'])
                    ->preloadRecordSelect()
                    ->attachAnother(false)
                    ->schema(fn (AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->label('Curso, minicurso o taller'),
                        TextInput::make('order_number')
                            ->label('Orden')
                            ->numeric()
                            ->minValue(1)
                            ->default(fn (): int => $this->getOwnerRecord()->trainingPrograms()->count() + 1)
                            ->required(),
                        Select::make('is_required')
                            ->label('Aplicación')
                            ->options([1 => 'Obligatorio', 0 => 'Opcional'])
                            ->default(1)
                            ->native(false)
                            ->required(),
                        TextInput::make('minimum_score')
                            ->label('Calificación mínima')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),
                    ])
                    ->after(fn () => $this->synchronizeAssignments()),
            ])
            ->recordActions([
                Action::make('administrar_programa')
                    ->label('Abrir programa')
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->url(fn (Model $record): string => ProgramControlPage::getUrl([
                        'record' => $record->getKey(),
                    ])),
                Action::make('configurar_vinculo')
                    ->label('Configurar')
                    ->icon(Heroicon::OutlinedAdjustmentsHorizontal)
                    ->fillForm(fn (Model $record): array => [
                        'order_number' => $record->pivot->order_number,
                        'is_required' => (int) $record->pivot->is_required,
                        'minimum_score' => $record->pivot->minimum_score,
                    ])
                    ->schema([
                        TextInput::make('order_number')
                            ->label('Orden')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                        Select::make('is_required')
                            ->label('Aplicación')
                            ->options([1 => 'Obligatorio', 0 => 'Opcional'])
                            ->native(false)
                            ->required(),
                        TextInput::make('minimum_score')
                            ->label('Calificación mínima')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),
                    ])
                    ->action(function (Model $record, array $data): void {
                        $this->getOwnerRecord()->trainingPrograms()->updateExistingPivot($record->getKey(), $data);
                        $this->synchronizeAssignments();
                    })
                    ->successNotificationTitle('Programa actualizado en la ruta'),
                DetachAction::make()
                    ->label('Quitar de la ruta')
                    ->after(fn () => $this->synchronizeAssignments()),
            ])
            ->toolbarActions([])
            ->emptyStateIcon(Heroicon::OutlinedAcademicCap)
            ->emptyStateHeading('La ruta aún no tiene programas')
            ->emptyStateDescription('Agrega uno o varios cursos, minicursos o talleres del catálogo institucional.')
            ->extraAttributes(['class' => 'pf-management-table']);
    }

    private function synchronizeAssignments(): void
    {
        $this->getOwnerRecord()
            ->userLearningPaths()
            ->each(fn ($assignment) => app(LearningPathProgressService::class)
                ->synchronizeAssignment($assignment));
    }
}
