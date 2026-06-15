<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\TrainingManagement\TrainingManagementCluster;
use App\Filament\Resources\ActivityTypeResource\Pages\ManageActivityTypes;
use App\Models\ActivityType;

class ActivityTypeResource extends InstitutionalResource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 3;

    protected static ?string $model = ActivityType::class;

    protected static ?string $cluster = TrainingManagementCluster::class;

    protected static ?string $modelLabel = 'Tipo de actividad';

    protected static ?string $pluralModelLabel = 'Tipos de actividad';

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
            'name' => 'default_generates_certificate',
            'label' => 'Genera constancia',
            'type' => 'toggle',
        ],
        3 => [
            'name' => 'default_generates_microcredential',
            'label' => 'Genera microcredencial',
            'type' => 'toggle',
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
            'name' => 'default_generates_certificate',
            'label' => 'Constancia',
            'type' => 'boolean',
        ],
        2 => [
            'name' => 'default_generates_microcredential',
            'label' => 'Microcredencial',
            'type' => 'boolean',
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
            'index' => ManageActivityTypes::route('/'),
        ];
    }
}
