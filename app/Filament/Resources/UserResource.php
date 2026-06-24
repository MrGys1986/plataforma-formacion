<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\UserManagement\UserManagementCluster;
use App\Filament\Resources\UserResource\Pages\ManageUsers;
use App\Models\User;
use Filament\Actions\EditAction;
use Filament\Resources\ResourceConfiguration;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends InstitutionalResource
{
    protected static ?string $configurationClass = ResourceConfiguration::class;

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

    protected static function modifyTable(Table $table): Table
    {
        return $table
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
            ->recordActions([
                EditAction::make()
                    ->iconButton()
                    ->tooltip('Editar usuario'),
            ])
            ->defaultSort('name')
            ->striped()
            ->searchPlaceholder('Buscar por nombre o correo')
            ->paginationPageOptions([10, 25, 50])
            ->persistSearchInSession()
            ->persistSortInSession()
            ->extraAttributes(['class' => 'pf-management-table']);
    }

    public static function getNavigationLabel(): string
    {
        $configuredView = static::getConfiguredView();

        return $configuredView['label'] ?? 'Usuarios';
    }

    public static function getNavigationParentItem(): ?string
    {
        return static::getConfiguration()?->getKey() ? 'Usuarios' : null;
    }

    public static function getNavigationSort(): ?int
    {
        $configuredView = static::getConfiguredView();

        return $configuredView['sort'] ?? parent::getNavigationSort();
    }

    public static function getNavigationIcon(): string|\BackedEnum|\Illuminate\Contracts\Support\Htmlable|null
    {
        return static::getConfiguration()?->getKey() ? null : parent::getNavigationIcon();
    }

    public static function getPluralModelLabel(): string
    {
        $configuredView = static::getConfiguredView();

        return $configuredView['label'] ?? 'Usuarios';
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        return match (static::getConfiguration()?->getKey()) {
            'superadministradores' => static::filterByRoles($query, ['Superadministrador']),
            'personal' => static::filterByRoles($query, ['Personal']),
            'responsables_area' => static::filterByRoles($query, ['Responsable Area']),
            'evaluadores' => static::filterByRoles($query, ['Evaluador']),
            'recursos_humanos' => static::filterByRoles($query, ['Recursos Humanos']),
            'calidad_academica' => static::filterByRoles($query, ['Calidad Academica']),
            'educacion_continua' => static::filterByRoles($query, ['Educacion Continua']),
            'profesores' => static::filterByRoles($query, ['Profesor']),
            'alumnos' => static::filterByRoles($query, ['Alumno']),
            'externos' => $query->where(function (Builder $builder): void {
                $builder
                    ->where('user_type', 'externo')
                    ->orWhere('profile_type', 'externo')
                    ->orWhereHas('roles', fn (Builder $roles): Builder => $roles->where('name', 'Externo'));
            }),
            default => $query,
        };
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageUsers::route('/'),
        ];
    }

    protected static function getConfiguredView(): ?array
    {
        return [
            'superadministradores' => [
                'label' => 'Superadministrador',
                'sort' => 1,
            ],
            'personal' => [
                'label' => 'Personal',
                'sort' => 2,
            ],
            'responsables_area' => [
                'label' => 'Responsable de área',
                'sort' => 3,
            ],
            'evaluadores' => [
                'label' => 'Evaluador',
                'sort' => 4,
            ],
            'recursos_humanos' => [
                'label' => 'Recursos Humanos',
                'sort' => 5,
            ],
            'calidad_academica' => [
                'label' => 'Calidad Académica',
                'sort' => 6,
            ],
            'educacion_continua' => [
                'label' => 'Educación Continua',
                'sort' => 7,
            ],
            'profesores' => [
                'label' => 'Profesor',
                'sort' => 8,
            ],
            'alumnos' => [
                'label' => 'Alumno',
                'sort' => 9,
            ],
            'externos' => [
                'label' => 'Externo',
                'sort' => 10,
            ],
        ][static::getConfiguration()?->getKey()] ?? null;
    }

    protected static function filterByRoles(Builder $query, array $roles): Builder
    {
        return $query->whereHas(
            'roles',
            fn (Builder $builder): Builder => $builder->whereIn('name', $roles),
        );
    }
}
