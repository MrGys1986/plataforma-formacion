<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\ResourceManagement\ResourceManagementCluster;
use App\Filament\Resources\WebinarResource\Pages\ManageWebinars;
use App\Models\Webinar;

class WebinarResource extends InstitutionalResource
{
    protected static ?int $navigationSort = 2;

    protected static ?string $model = Webinar::class;

    protected static ?string $cluster = ResourceManagementCluster::class;

    protected static ?string $modelLabel = 'Webinar';

    protected static ?string $pluralModelLabel = 'Webinars';

    protected static ?string $recordTitleAttribute = 'title';

    protected static array $formFields = [
        0 => [
            'name' => 'area_id',
            'label' => 'Área',
            'type' => 'relation',
            'relationship' => 'area',
        ],
        1 => [
            'name' => 'title',
            'label' => 'Título',
            'required' => true,
        ],
        2 => [
            'name' => 'description',
            'label' => 'Descripción',
            'type' => 'textarea',
        ],
        3 => [
            'name' => 'speaker',
            'label' => 'Ponente',
        ],
        4 => [
            'name' => 'start_datetime',
            'label' => 'Inicio',
            'type' => 'datetime',
        ],
        5 => [
            'name' => 'end_datetime',
            'label' => 'Fin',
            'type' => 'datetime',
        ],
        6 => [
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
            'name' => 'speaker',
            'label' => 'Ponente',
        ],
        2 => [
            'name' => 'start_datetime',
            'label' => 'Inicio',
            'type' => 'datetime',
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
            'index' => ManageWebinars::route('/'),
        ];
    }
}
