<?php

namespace App\Filament\Resources\LearningPathResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\AttachAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DetachAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CompetencyDefinitionsRelationManager extends RelationManager
{
    protected static string $relationship = 'competencyDefinitions';

    protected static ?string $title = 'Competencias';

    protected static string|\BackedEnum|null $icon = Heroicon::OutlinedLightBulb;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('area_id')
                    ->label('Área responsable')
                    ->relationship('area', 'name')
                    ->default(fn (): ?int => $this->getOwnerRecord()->area_id)
                    ->searchable()
                    ->preload(),
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                TextInput::make('slug')
                    ->label('Identificador')
                    ->required()
                    ->unique(ignoreRecord: true),
                Select::make('status')
                    ->label('Estado')
                    ->options([
                        'activo' => 'Activo',
                        'inactivo' => 'Inactivo',
                        'borrador' => 'Borrador',
                    ])
                    ->default('activo')
                    ->native(false)
                    ->required(),
                Textarea::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),
                Textarea::make('objective')
                    ->label('Objetivo de la competencia')
                    ->columnSpanFull(),
                Textarea::make('completion_criteria')
                    ->label('Criterios de logro')
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Competencia')
                    ->icon(Heroicon::OutlinedLightBulb)
                    ->weight('semibold')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('area.name')
                    ->label('Área')
                    ->placeholder('Sin asignar'),
                TextColumn::make('pivot.is_required')
                    ->label('Aplicación')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Obligatoria' : 'Opcional')
                    ->color(fn (bool $state): string => $state ? 'primary' : 'gray'),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Crear competencia')
                    ->icon(Heroicon::OutlinedPlus)
                    ->mutateDataUsing(fn (array $data): array => [
                        ...$data,
                        'created_by' => auth()->id(),
                    ]),
                AttachAction::make()
                    ->label('Vincular existente')
                    ->icon(Heroicon::OutlinedLink)
                    ->preloadRecordSelect()
                    ->attachAnother(false)
                    ->schema(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        TextInput::make('order_number')
                            ->label('Orden')
                            ->numeric()
                            ->minValue(1)
                            ->default(fn (): int => $this->getOwnerRecord()->competencyDefinitions()->count() + 1)
                            ->required(),
                        Select::make('is_required')
                            ->label('Aplicación')
                            ->options([1 => 'Obligatoria', 0 => 'Opcional'])
                            ->default(1)
                            ->native(false)
                            ->required(),
                    ]),
            ])
            ->recordActions([
                Action::make('configurar_vinculo')
                    ->label('Configurar')
                    ->icon(Heroicon::OutlinedAdjustmentsHorizontal)
                    ->fillForm(fn (Model $record): array => [
                        'order_number' => $record->pivot->order_number,
                        'is_required' => (int) $record->pivot->is_required,
                    ])
                    ->schema([
                        TextInput::make('order_number')
                            ->label('Orden')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                        Select::make('is_required')
                            ->label('Aplicación')
                            ->options([1 => 'Obligatoria', 0 => 'Opcional'])
                            ->native(false)
                            ->required(),
                    ])
                    ->action(fn (Model $record, array $data) => $this->getOwnerRecord()
                        ->competencyDefinitions()
                        ->updateExistingPivot($record->getKey(), $data))
                    ->successNotificationTitle('Competencia actualizada'),
                EditAction::make()
                    ->label('Editar'),
                DetachAction::make()
                    ->label('Quitar de la ruta'),
            ])
            ->toolbarActions([])
            ->emptyStateIcon(Heroicon::OutlinedLightBulb)
            ->emptyStateHeading('La ruta aún no tiene competencias')
            ->emptyStateDescription('Crea una competencia nueva o vincula una definición existente.')
            ->extraAttributes(['class' => 'pf-management-table']);
    }
}
