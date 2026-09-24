<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\UserManagement\UserManagementCluster;
use App\Filament\Resources\UserResource\Pages\ManageUsers;
use App\Models\User;
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
use Spatie\Permission\Models\Role;

class UserResource extends InstitutionalResource
{
    protected static ?int $navigationSort = 1;

    protected static ?string $model = User::class;

    protected static ?string $cluster = UserManagementCluster::class;

    protected static ?string $modelLabel = 'Usuario';

    protected static ?string $pluralModelLabel = 'Usuarios';

    protected static ?string $recordTitleAttribute = 'name';

    protected static array $formFields = [
        [
            'name' => 'name',
            'label' => 'Nombre',
            'required' => true,
        ],
        [
            'name' => 'email',
            'label' => 'Correo electrónico',
            'required' => true,
        ],
        [
            'name' => 'avatar_file_id',
            'label' => 'Fotografía de perfil',
            'type' => 'file',
            'directory' => 'profile-photos',
            'public_image' => true,
            'accepted_types' => ['image/jpeg', 'image/png', 'image/webp'],
            'max_size' => 3072,
        ],
        [
            'name' => 'password',
            'label' => 'Contraseña',
            'type' => 'password',
        ],
        [
            'name' => 'roles',
            'label' => 'Roles',
            'type' => 'relation',
            'relationship' => 'roles',
            'multiple' => true,
            'visible_for_role' => 'Superadministrador',
        ],
        [
            'name' => 'user_type',
            'label' => 'Tipo de usuario',
            'type' => 'select',
            'options' => [
                'interno' => 'Interno',
                'externo' => 'Externo',
            ],
        ],
        [
            'name' => 'profile_type',
            'label' => 'Perfil',
            'type' => 'select',
            'options' => [
                'personal' => 'Personal universitario',
                'profesor' => 'Profesor participante',
                'alumno' => 'Alumno',
                'externo' => 'Participante externo',
                'evaluador' => 'Evaluador',
                'responsable_area' => 'Responsable de área',
            ],
        ],
        [
            'name' => 'area_id',
            'label' => 'Área',
            'type' => 'relation',
            'relationship' => 'area',
        ],
        [
            'name' => 'curp',
            'label' => 'CURP',
        ],
        [
            'name' => 'external_institution',
            'label' => 'Institución de procedencia',
        ],
        [
            'name' => 'phone',
            'label' => 'Teléfono',
        ],
        [
            'name' => 'status',
            'label' => 'Estado',
            'type' => 'select',
            'options' => [
                'activo' => 'Activo',
                'inactivo' => 'Inactivo',
                'suspendido' => 'Suspendido',
            ],
        ],
    ];

    protected static array $tableColumns = [
        [
            'name' => 'name',
            'label' => 'Nombre',
            'searchable' => true,
        ],
        [
            'name' => 'email',
            'label' => 'Correo electrónico',
            'searchable' => true,
        ],
        [
            'name' => 'roles.name',
            'label' => 'Roles',
        ],
        [
            'name' => 'area.name',
            'label' => 'Área',
        ],
        [
            'name' => 'user_type',
            'label' => 'Tipo',
        ],
        [
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
                'icon' => Heroicon::OutlinedUsers,
                'label' => 'Directorio de usuarios',
            ]))
            ->description('Selecciona el rol que deseas consultar y utiliza los filtros para localizar cuentas específicas.')
            ->headerActions([
                CreateAction::make()
                    ->label('Nuevo usuario')
                    ->icon(Heroicon::OutlinedUserPlus)
                    ->color('primary')
                    ->modalHeading('Registrar usuario')
                    ->modalDescription('Completa los datos de acceso, perfil y adscripción del usuario.')
                    ->modalIcon(Heroicon::OutlinedUserPlus)
                    ->modalIconColor('primary')
                    ->modalWidth(Width::TwoExtraLarge)
                    ->modalSubmitActionLabel('Guardar')
                    ->createAnother(false)
                    ->successNotificationTitle('Usuario registrado correctamente'),
            ])
            ->columns([
                TextColumn::make('name')
                    ->label('Usuario')
                    ->icon(Heroicon::OutlinedUserCircle)
                    ->iconColor('primary')
                    ->weight('semibold')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Correo electrónico')
                    ->icon(Heroicon::OutlinedEnvelope)
                    ->iconColor('gray')
                    ->copyable()
                    ->copyMessage('Correo copiado')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->label('Rol')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Superadministrador' => 'danger',
                        'Recursos Humanos' => 'success',
                        'Calidad Academica' => 'info',
                        'Educacion Continua' => 'warning',
                        'Responsable Area' => 'primary',
                        default => 'gray',
                    })
                    ->limitList(2)
                    ->expandableLimitedList(),
                TextColumn::make('area.name')
                    ->label('Área')
                    ->icon(Heroicon::OutlinedBuildingOffice)
                    ->iconColor('primary')
                    ->placeholder('Sin área')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user_type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => ucfirst($state ?: 'Sin definir'))
                    ->color(fn (?string $state): string => $state === 'externo' ? 'warning' : 'info'),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->icon(fn (?string $state): Heroicon => match ($state) {
                        'activo' => Heroicon::OutlinedCheckCircle,
                        'suspendido' => Heroicon::OutlinedShieldCheck,
                        default => Heroicon::OutlinedIdentification,
                    })
                    ->formatStateUsing(fn (?string $state): string => ucfirst($state ?: 'Sin definir'))
                    ->color(fn (?string $state): string => match ($state) {
                        'activo' => 'success',
                        'suspendido' => 'warning',
                        'inactivo' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Rol')
                    ->placeholder('Selecciona el rol a consultar')
                    ->options(fn (): array => Role::query()
                        ->where('guard_name', 'web')
                        ->orderBy('name')
                        ->pluck('name', 'name')
                        ->all())
                    ->searchable()
                    ->preload()
                    ->query(fn (Builder $query, array $data): Builder => $query->when(
                        filled($data['value'] ?? null),
                        fn (Builder $users): Builder => $users->whereHas(
                            'roles',
                            fn (Builder $roles): Builder => $roles->where('name', $data['value']),
                        ),
                    )),
                SelectFilter::make('user_type')
                    ->label('Tipo de usuario')
                    ->placeholder('Todos los tipos')
                    ->options([
                        'interno' => 'Interno',
                        'externo' => 'Externo',
                    ]),
                SelectFilter::make('status')
                    ->label('Estado')
                    ->placeholder('Todos los estados')
                    ->options([
                        'activo' => 'Activo',
                        'inactivo' => 'Inactivo',
                        'suspendido' => 'Suspendido',
                    ]),
            ], FiltersLayout::AboveContent)
            ->filtersFormColumns([
                'default' => 1,
                'md' => 3,
            ])
            ->deferFilters(false)
            ->modifyQueryUsing(function (Builder $query, ManageUsers $livewire): Builder {
                $selectedRole = data_get($livewire->getTableFilterState('role'), 'value');

                if (filled($selectedRole) || $livewire->hasTableSearch()) {
                    return $query;
                }

                return $query->whereRaw('1 = 0');
            })
            ->recordActions([
                EditAction::make()
                    ->button()
                    ->label('Editar')
                    ->icon(Heroicon::OutlinedPencilSquare)
                    ->color('primary')
                    ->tooltip('Editar usuario')
                    ->modalHeading(fn (User $record): string => "Editar a {$record->name}")
                    ->modalDescription('Actualiza los datos necesarios y confirma para guardar los cambios.')
                    ->modalIcon(Heroicon::OutlinedPencilSquare)
                    ->modalIconColor('primary')
                    ->modalWidth(Width::TwoExtraLarge)
                    ->modalSubmitActionLabel('Guardar')
                    ->successNotificationTitle('Usuario actualizado correctamente'),
            ])
            ->defaultSort('name')
            ->striped()
            ->searchPlaceholder('Buscar por nombre o correo')
            ->paginationPageOptions([10, 25, 50])
            ->defaultPaginationPageOption(25)
            ->persistSearchInSession(false)
            ->persistFiltersInSession(false)
            ->persistSortInSession()
            ->emptyStateIcon(Heroicon::OutlinedFunnel)
            ->emptyStateHeading(fn (ManageUsers $livewire): string => $livewire->hasTableSearch()
                ? 'No se encontraron usuarios'
                : 'Selecciona un rol para consultar usuarios')
            ->emptyStateDescription(fn (ManageUsers $livewire): string => $livewire->hasTableSearch()
                ? 'Prueba con otro nombre o correo electrónico.'
                : 'La tabla permanece vacía hasta elegir un rol. También puedes buscar directamente por nombre o correo.')
            ->extraAttributes(['class' => 'pf-management-table pf-user-table']);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageUsers::route('/'),
        ];
    }
}
