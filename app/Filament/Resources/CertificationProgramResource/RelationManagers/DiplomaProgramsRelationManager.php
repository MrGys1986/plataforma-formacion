<?php

namespace App\Filament\Resources\CertificationProgramResource\RelationManagers;

use App\Filament\Resources\DiplomaProgramResource;
use App\Models\LearningPath;
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
                Action::make('abrir')->label('Gestionar diplomado')
                    ->url(fn (Model $record): string => DiplomaProgramResource::getUrl('edit', ['record' => $record])),
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
                DetachAction::make()->label('Quitar de la certificación')
                    ->after(fn () => $this->synchronizeAssignments()),
            ])
            ->emptyStateHeading('Sin diplomados vinculados')
            ->emptyStateDescription('Agrega diplomados opcionales o requeridos para esta certificación.');
    }

    private function synchronizeAssignments(): void
    {
        $certification = $this->getOwnerRecord()->unsetRelations();

        LearningPath::query()
            ->whereHas('certificationPrograms', fn (Builder $query) => $query->whereKey($certification->id))
            ->each(fn (LearningPath $path) => $path->userLearningPaths()
                ->each(fn ($assignment) => app(LearningPathProgressService::class)->synchronizeAssignment($assignment)));
    }
}
