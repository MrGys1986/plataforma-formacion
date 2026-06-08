<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\SystemAdministration\SystemAdministrationCluster;
use App\Filament\Resources\UserResource\Pages\ManageUsers;
use App\Models\User;

class UserResource extends InstitutionalResource
{
    protected static ?string $model = User::class;

    protected static ?string $cluster = SystemAdministrationCluster::class;

    protected static ?string $modelLabel = 'Usuario';

    protected static ?string $pluralModelLabel = 'Usuarios';

    protected static ?string $recordTitleAttribute = 'name';

    protected static array $formFields = [
        0 => [
            'name' => 'name',
            'label' => 'Nombre',
            'required' => true,
        ],
        1 => [
            'name' => 'email',
            'label' => 'Correo electrónico',
            'required' => true,
        ],
        2 => [
            'name' => 'password',
            'label' => 'Contraseña',
            'type' => 'password',
        ],
        3 => [
            'name' => 'area_id',
            'label' => 'Área',
            'type' => 'relation',
            'relationship' => 'area',
        ],
        4 => [
            'name' => 'phone',
            'label' => 'Teléfono',
        ],
        5 => [
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
        0 => [
            'name' => 'name',
            'label' => 'Nombre',
            'searchable' => true,
        ],
        1 => [
            'name' => 'email',
            'label' => 'Correo electrónico',
            'searchable' => true,
        ],
        2 => [
            'name' => 'area.name',
            'label' => 'Área',
        ],
        3 => [
            'name' => 'status',
            'label' => 'Estado',
        ],
    ];

    protected static ?string $statusColumn = 'status';

    protected static bool $readOnly = false;

    public static function getPages(): array
    {
        return [
            'index' => ManageUsers::route('/'),
        ];
    }
}
