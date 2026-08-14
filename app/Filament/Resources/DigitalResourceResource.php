<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\ResourceManagement\ResourceManagementCluster;
use App\Filament\Resources\DigitalResourceResource\Pages\ManageDigitalResources;
use App\Models\DigitalResource;
use Illuminate\Database\Eloquent\Builder;

class DigitalResourceResource extends InstitutionalResource
{
    protected static ?int $navigationSort = 1;

    protected static ?string $model = DigitalResource::class;

    protected static ?string $cluster = ResourceManagementCluster::class;

    protected static ?string $modelLabel = 'Recurso digital';

    protected static ?string $pluralModelLabel = 'Recursos digitales';

    protected static ?string $recordTitleAttribute = 'title';

    protected static array $tableColumns = [
        [
            'name' => 'title',
            'label' => 'Título',
            'searchable' => true,
        ],
        [
            'name' => 'resource_type',
            'label' => 'Tipo',
        ],
        [
            'name' => 'area.name',
            'label' => 'Área',
        ],
        [
            'name' => 'visibility',
            'label' => 'Visibilidad',
        ],
        [
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

    protected static function getFormFields(): array
    {
        return [
            [
                'name' => 'area_id',
                'label' => 'Área',
                'type' => 'relation',
                'relationship' => 'area',
            ],
            [
                'name' => 'activity_id',
                'label' => 'Actividad',
                'type' => 'relation',
                'relationship' => 'activity',
                'default' => fn (): ?int => request()->filled('activity')
                    ? request()->integer('activity')
                    : null,
                'disabled' => fn (): bool => request()->filled('activity'),
                'dehydrated' => true,
            ],
            [
                'name' => 'title',
                'label' => 'Título',
                'required' => true,
            ],
            [
                'name' => 'description',
                'label' => 'Descripción',
                'type' => 'textarea',
            ],
            [
                'name' => 'resource_type',
                'label' => 'Tipo de recurso',
            ],
            [
                'name' => 'external_url',
                'label' => 'URL externa',
            ],
            [
                'name' => 'file_upload_id',
                'label' => 'Archivo del recurso',
                'type' => 'file',
                'directory' => 'digital-resources',
                'accepted_types' => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png'],
            ],
            [
                'name' => 'visibility',
                'label' => 'Visibilidad',
            ],
            [
                'name' => 'status',
                'label' => 'Estado',
            ],
        ];
    }

    protected static function applyContextToQuery(Builder $query): Builder
    {
        if (! request()->filled('activity')) {
            return $query;
        }

        return $query->where('activity_id', request()->integer('activity'));
    }
}
