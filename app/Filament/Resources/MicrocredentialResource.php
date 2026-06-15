<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\CredentialManagement\CredentialManagementCluster;
use App\Filament\Resources\MicrocredentialResource\Pages\ManageMicrocredentials;
use App\Models\Microcredential;

class MicrocredentialResource extends InstitutionalResource
{
    protected static ?int $navigationSort = 1;

    protected static ?string $model = Microcredential::class;

    protected static ?string $cluster = CredentialManagementCluster::class;

    protected static ?string $modelLabel = 'Microcredencial';

    protected static ?string $pluralModelLabel = 'Microcredenciales';

    protected static ?string $recordTitleAttribute = 'name';

    protected static array $formFields = [
        0 => [
            'name' => 'user_id',
            'label' => 'Titular',
            'type' => 'relation',
            'relationship' => 'user',
            'required' => true,
        ],
        1 => [
            'name' => 'activity_id',
            'label' => 'Actividad',
            'type' => 'relation',
            'relationship' => 'activity',
        ],
        2 => [
            'name' => 'certificate_id',
            'label' => 'Constancia',
            'type' => 'relation',
            'relationship' => 'certificate',
            'title' => 'folio',
        ],
        3 => [
            'name' => 'name',
            'label' => 'Nombre',
            'required' => true,
        ],
        4 => [
            'name' => 'description',
            'label' => 'Descripción',
            'type' => 'textarea',
        ],
        5 => [
            'name' => 'json_payload',
            'label' => 'Carga JSON',
            'type' => 'json',
        ],
        6 => [
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
            'name' => 'user.name',
            'label' => 'Titular',
        ],
        2 => [
            'name' => 'activity.name',
            'label' => 'Actividad',
        ],
        3 => [
            'name' => 'issued_at',
            'label' => 'Emisión',
            'type' => 'datetime',
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
            'index' => ManageMicrocredentials::route('/'),
        ];
    }
}
