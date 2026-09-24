<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\UserManagement\UserManagementCluster;
use App\Filament\Resources\AreaResource\Pages\ManageAreas;
use App\Models\Area;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
            'type' => 'select',
            'options' => [
                'académica' => 'Académica',
                'administrativa' => 'Administrativa',
            ],
            'required' => true,
        ],
        3 => [
            'name' => 'responsible_user_id',
            'label' => 'Responsable',
            'type' => 'relation',
            'relationship' => 'responsibleUser',
            'role' => 'Responsable Area',
        ],
        4 => [
            'name' => 'status',
            'label' => 'Estado',
            'type' => 'select',
            'options' => [
                'activa' => 'Activa',
                'inactiva' => 'Inactiva',
            ],
            'default' => 'activa',
            'required' => true,
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

    public static function form(Schema $schema): Schema
    {
        return parent::form($schema)
            ->columns([
                'default' => 1,
                'md' => 2,
            ]);
    }

    protected static function modifyTable(Table $table): Table
    {
        return $table
            ->heading(view('filament.partials.table-heading', [
                'icon' => Heroicon::OutlinedBuildingOffice2,
                'label' => 'Catálogo de áreas',
            ]))
            ->description('Consulta la estructura institucional, sus responsables y el estado de cada área.')
            ->headerActions([
                CreateAction::make()
                    ->label('Nueva área')
                    ->icon(Heroicon::OutlinedBuildingOffice2)
                    ->color('primary')
                    ->modalHeading('Registrar área')
                    ->modalDescription('Define los datos generales y asigna a la persona responsable.')
                    ->modalIcon(Heroicon::OutlinedBuildingOffice2)
                    ->modalIconColor('primary')
                    ->modalWidth(Width::Large)
                    ->modalSubmitActionLabel('Guardar')
                    ->createAnother(false)
                    ->successNotificationTitle('Área registrada correctamente'),
            ])
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
                    ->color(fn (?string $state): string => match ($state) {
                        'académica', 'academica' => 'info',
                        'administrativa' => 'primary',
                        default => 'gray',
                    })
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
                        'activa' => Heroicon::OutlinedCheckCircle,
                        default => Heroicon::OutlinedIdentification,
                    })
                    ->formatStateUsing(fn (?string $state): string => ucfirst($state ?: 'Sin definir'))
                    ->color(fn (?string $state): string => match ($state) {
                        'activa' => 'success',
                        'inactiva' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('area_type')
                    ->label('Tipo de área')
                    ->placeholder('Todos los tipos')
                    ->options([
                        'académica' => 'Académica',
                        'administrativa' => 'Administrativa',
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query->when(
                        filled($data['value'] ?? null),
                        fn (Builder $areas): Builder => $data['value'] === 'académica'
                            ? $areas->whereIn('area_type', ['académica', 'academica'])
                            : $areas->where('area_type', $data['value']),
                    )),
                SelectFilter::make('status')
                    ->label('Estado')
                    ->placeholder('Todos los estados')
                    ->options([
                        'activa' => 'Activa',
                        'inactiva' => 'Inactiva',
                    ]),
                SelectFilter::make('responsible_user_id')
                    ->label('Responsable')
                    ->placeholder('Todas las personas responsables')
                    ->relationship(
                        'responsibleUser',
                        'name',
                        fn (Builder $query): Builder => $query->role('Responsable Area'),
                    )
                    ->searchable(),
            ], FiltersLayout::AboveContent)
            ->filtersFormColumns([
                'default' => 1,
                'md' => 3,
            ])
            ->deferFilters(false)
            ->recordActions([
                EditAction::make()
                    ->button()
                    ->label('Editar')
                    ->icon(Heroicon::OutlinedPencilSquare)
                    ->color('primary')
                    ->tooltip('Editar área')
                    ->modalHeading(fn (Area $record): string => "Editar {$record->name}")
                    ->modalDescription('Actualiza la información del área y confirma para guardar los cambios.')
                    ->modalIcon(Heroicon::OutlinedPencilSquare)
                    ->modalIconColor('primary')
                    ->modalWidth(Width::Large)
                    ->modalSubmitActionLabel('Guardar')
                    ->successNotificationTitle('Área actualizada correctamente'),
            ])
            ->defaultSort('name')
            ->striped()
            ->searchPlaceholder('Buscar área o responsable')
            ->paginationPageOptions([10, 25, 50])
            ->defaultPaginationPageOption(25)
            ->persistSearchInSession(false)
            ->persistFiltersInSession(false)
            ->persistSortInSession()
            ->emptyStateIcon(Heroicon::OutlinedBuildingOffice2)
            ->emptyStateHeading('No se encontraron áreas')
            ->emptyStateDescription('Prueba con otros criterios de búsqueda o registra una nueva área.')
            ->extraAttributes(['class' => 'pf-management-table pf-area-table']);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAreas::route('/'),
        ];
    }
}
