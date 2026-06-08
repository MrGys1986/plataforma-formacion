<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\ContinuingEducation\ContinuingEducationCluster;
use App\Filament\Resources\DigitalResourceResource\Pages\ManageDigitalResources;
use App\Models\DigitalResource;

class DigitalResourceResource extends InstitutionalResource
{
    protected static ?string $model = DigitalResource::class;

    protected static ?string $cluster = ContinuingEducationCluster::class;

    protected static ?string $modelLabel = 'Recurso digital';

    protected static ?string $pluralModelLabel = 'Recursos digitales';

    protected static ?string $recordTitleAttribute = 'title';

    protected static array $formFields = [
        0 => [
            'name' => 'area_id',
            'label' => 'Área',
            'type' => 'relation',
            'relationship' => 'area',
        ],
        1 => [
            'name' => 'activity_id',
            'label' => 'Actividad',
            'type' => 'relation',
            'relationship' => 'activity',
        ],
        2 => [
            'name' => 'title',
            'label' => 'Título',
            'required' => true,
        ],
        3 => [
            'name' => 'description',
            'label' => 'Descripción',
            'type' => 'textarea',
        ],
        4 => [
            'name' => 'resource_type',
            'label' => 'Tipo de recurso',
        ],
        5 => [
            'name' => 'external_url',
            'label' => 'URL externa',
        ],
        6 => [
            'name' => 'visibility',
            'label' => 'Visibilidad',
        ],
        7 => [
            'name' => 'status',
            'label' => 'Estado',
        ],
    ];

    protected static array $tableColumns = [
        0 => [
            'name' => 'title',
            'label' => 'Título',
            'searchable' => true,
        ],
        1 => [
            'name' => 'resource_type',
            'label' => 'Tipo',
        ],
        2 => [
            'name' => 'area.name',
            'label' => 'Área',
        ],
        3 => [
            'name' => 'visibility',
            'label' => 'Visibilidad',
        ],
        4 => [
            'name' => 'status',
            'label' => 'Estado',
        ],
    ];

    protected static ?string $statusColumn = 'status';

    protected static bool $readOnly = false;

    public static function getPages(): array
    {
        return [
            'index' => ManageDigitalResources::route('/'),
        ];
    }
}
