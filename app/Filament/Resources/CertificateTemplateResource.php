<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\CredentialManagement\CredentialManagementCluster;
use App\Filament\Resources\CertificateTemplateResource\Pages\ManageCertificateTemplates;
use App\Models\CertificateTemplate;

class CertificateTemplateResource extends InstitutionalResource
{
    protected static ?string $model = CertificateTemplate::class;

    protected static ?string $cluster = CredentialManagementCluster::class;

    protected static ?string $modelLabel = 'Plantilla de constancia';

    protected static ?string $pluralModelLabel = 'Plantillas de constancias';

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
            'name' => 'certificate_type',
            'label' => 'Tipo de constancia',
        ],
        3 => [
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
            'name' => 'certificate_type',
            'label' => 'Tipo',
        ],
        2 => [
            'name' => 'status',
            'label' => 'Estado',
        ],
    ];

    protected static ?string $statusColumn = 'status';

    protected static bool $readOnly = false;

    public static function getPages(): array
    {
        return [
            'index' => ManageCertificateTemplates::route('/'),
        ];
    }
}
