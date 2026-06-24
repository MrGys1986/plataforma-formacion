<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\UserManagement\UserManagementCluster;
use App\Filament\Resources\AreaResource\Pages\ManageAreas;
use App\Models\Area;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AreaResource extends InstitutionalResource
{
    protected static ?int $navigationSort = 2;

    protected static ?string $model = Area::class;

    protected static ?string $cluster = UserManagementCluster::class;

    protected static ?string $modelLabel = 'Área';

    protected static ?string $pluralModelLabel = 'Áreas';

    protected static ?string $recordTitleAttribute = 'name';

    protected static array $formFields = [
        0 => [
            'name' => 'name',
            'label' => 'Nombre',
            'required' => true,
        ],
        1 => [
            'name' => 'description',
            'label' => 'Descripción',
            'type' => 'textarea',
        ],
        2 => [
            'name' => 'area_type',
            'label' => 'Tipo de área',
            'required' => true,
        ],
        3 => [
            'name' => 'responsible_user_id',
            'label' => 'Responsable',
            'type' => 'relation',
            'relationship' => 'responsibleUser',
        ],
        4 => [
            'name' => 'status',
            'label' => 'Estado',
        ],
    ];

    protected static array $tableColumns = [
        0 => [
            'name' => 'name',
            'label' => 'Nombre',
            'searchable' => true,
        ],
        1 => [
            'name' => 'area_type',
            'label' => 'Tipo',
        ],
        2 => [
            'name' => 'responsibleUser.name',
            'label' => 'Responsable',
        ],
        3 => [
            'name' => 'status',
            'label' => 'Estado',
        ],
    ];

    protected static ?string $statusColumn = 'status';

    protected static bool $readOnly = false;

    protected static function modifyTable(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Área')
                    ->icon(Heroicon::OutlinedBuildingOffice)
                    ->iconColor('primary')
                    ->weight('semibold')
                    ->description(fn (Area $record): ?string => $record->description)
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('area_type')
                    ->label('Tipo de área')
                    ->badge()
                    ->icon(Heroicon::OutlinedBriefcase)
                    ->formatStateUsing(fn (?string $state): string => ucfirst($state ?: 'Sin definir'))
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('responsibleUser.name')
                    ->label('Responsable')
                    ->icon(Heroicon::OutlinedUserCircle)
                    ->iconColor('primary')
                    ->placeholder('Sin responsable')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->icon(fn (?string $state): Heroicon => match ($state) {
                        'activo' => Heroicon::OutlinedCheckCircle,
                        default => Heroicon::OutlinedIdentification,
                    })
                    ->formatStateUsing(fn (?string $state): string => ucfirst($state ?: 'Sin definir'))
                    ->color(fn (?string $state): string => match ($state) {
                        'activo' => 'success',
                        'inactivo' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make()
                    ->iconButton()
                    ->tooltip('Editar área'),
            ])
            ->defaultSort('name')
            ->striped()
            ->searchPlaceholder('Buscar área o responsable')
            ->paginationPageOptions([10, 25, 50])
            ->persistSearchInSession()
            ->persistSortInSession()
            ->extraAttributes(['class' => 'pf-management-table']);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAreas::route('/'),
        ];
    }
}
