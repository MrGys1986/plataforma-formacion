<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\UserManagement\UserManagementCluster;
use App\Filament\Resources\AreaResource\Pages\ManageAreas;
use App\Models\Area;

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

    public static function getPages(): array
    {
        return [
            'index' => ManageAreas::route('/'),
        ];
    }
}
