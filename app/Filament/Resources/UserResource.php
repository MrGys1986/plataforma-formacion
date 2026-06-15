<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\UserManagement\UserManagementCluster;
use App\Filament\Resources\UserResource\Pages\ManageUsers;
use App\Models\User;
use Filament\Resources\ResourceConfiguration;
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
            'administracion' => static::filterByRoles($query, [
                'Superadministrador',
                'Recursos Humanos',
                'Calidad Academica',
                'Educacion Continua',
            ]),
            'personal' => static::filterByRoles($query, ['Personal']),
            'responsables_area' => static::filterByRoles($query, ['Responsable Area']),
            'evaluadores' => static::filterByRoles($query, ['Evaluador']),
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
            'administracion' => [
                'label' => 'Administración',
                'sort' => 1,
            ],
            'personal' => [
                'label' => 'Personal universitario',
                'sort' => 2,
            ],
            'responsables_area' => [
                'label' => 'Responsables de área',
                'sort' => 3,
            ],
            'evaluadores' => [
                'label' => 'Evaluadores',
                'sort' => 4,
            ],
            'profesores' => [
                'label' => 'Profesores',
                'sort' => 5,
            ],
            'alumnos' => [
                'label' => 'Alumnos',
                'sort' => 6,
            ],
            'externos' => [
                'label' => 'Externos',
                'sort' => 7,
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
