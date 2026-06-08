<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\CredentialManagement\CredentialManagementCluster;
use App\Filament\Resources\CertificateResource\Pages\ManageCertificates;
use App\Models\Certificate;

class CertificateResource extends InstitutionalResource
{
    protected static ?string $model = Certificate::class;

    protected static ?string $cluster = CredentialManagementCluster::class;

    protected static ?string $modelLabel = 'Constancia';

    protected static ?string $pluralModelLabel = 'Constancias';

    protected static ?string $recordTitleAttribute = 'folio';

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
            'name' => 'certificate_template_id',
            'label' => 'Plantilla',
            'type' => 'relation',
            'relationship' => 'certificateTemplate',
        ],
        3 => [
            'name' => 'folio',
            'label' => 'Folio',
            'required' => true,
        ],
        4 => [
            'name' => 'certificate_type',
            'label' => 'Tipo de constancia',
        ],
        5 => [
            'name' => 'issued_at',
            'label' => 'Fecha de emisión',
            'type' => 'datetime',
        ],
        6 => [
            'name' => 'status',
            'label' => 'Estado',
        ],
    ];

    protected static array $tableColumns = [
        0 => [
            'name' => 'folio',
            'label' => 'Folio',
            'searchable' => true,
        ],
        1 => [
            'name' => 'user.name',
            'label' => 'Titular',
            'searchable' => true,
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
            'index' => ManageCertificates::route('/'),
        ];
    }
}
