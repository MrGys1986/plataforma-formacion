<?php

namespace App\Filament\Resources\LearningPathResource\RelationManagers;

use App\Filament\Resources\CertificationProgramResource;
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

class CertificationProgramsRelationManager extends RelationManager
{
    protected static string $relationship = 'certificationPrograms';

    protected static ?string $title = 'Certificaciones';

    protected static string|\BackedEnum|null $icon = Heroicon::OutlinedCheckBadge;

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')->label('Certificación')->searchable()->weight('semibold'),
                TextColumn::make('pivot.order_number')->label('Orden'),
                TextColumn::make('pivot.is_required')->label('Aplicación')->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Obligatoria' : 'Opcional'),
                TextColumn::make('generates_microcredential')->label('Microcredencial')->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Sí' : 'No'),
            ])
            ->headerActions([
                AttachAction::make()->label('Agregar certificación')->preloadRecordSelect()->attachAnother(false)
                    ->schema(fn (AttachAction $action): array => [
                        $action->getRecordSelect()->label('Certificación'),
                        TextInput::make('order_number')->label('Orden')->numeric()->minValue(1)->default(1)->required(),
                        Select::make('is_required')->label('Aplicación')->options([1 => 'Obligatoria', 0 => 'Opcional'])->default(1)->required(),
                    ])->after(fn () => $this->synchronizeAssignments()),
            ])
            ->recordActions([
                Action::make('abrir')->label('Gestionar certificación')->url(fn (Model $record): string => CertificationProgramResource::getUrl('edit', ['record' => $record])),
                Action::make('configurar')->label('Configurar')->fillForm(fn (Model $record): array => [
                    'order_number' => $record->pivot->order_number,
                    'is_required' => (int) $record->pivot->is_required,
                ])->schema([
                    TextInput::make('order_number')->label('Orden')->numeric()->minValue(1)->required(),
                    Select::make('is_required')->label('Aplicación')->options([1 => 'Obligatoria', 0 => 'Opcional'])->required(),
                ])->action(function (Model $record, array $data): void {
                    $this->getOwnerRecord()->certificationPrograms()->updateExistingPivot($record->getKey(), $data);
                    $this->synchronizeAssignments();
                }),
                DetachAction::make()->label('Quitar de la ruta')->after(fn () => $this->synchronizeAssignments()),
            ])
            ->emptyStateHeading('Sin certificaciones vinculadas')
            ->emptyStateDescription('Crea una certificación en este apartado o vincula una existente.');
    }

    private function synchronizeAssignments(): void
    {
        $this->getOwnerRecord()->unsetRelations()->userLearningPaths()
            ->each(fn ($assignment) => app(LearningPathProgressService::class)->synchronizeAssignment($assignment));
    }
}
