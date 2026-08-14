<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\CredentialManagement\CredentialManagementCluster;
use App\Filament\Resources\CertificateResource\Pages\ManageCertificates;
use App\Models\Certificate;
use Illuminate\Database\Eloquent\Builder;

class CertificateResource extends InstitutionalResource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 2;

    protected static ?string $model = Certificate::class;

    protected static ?string $cluster = CredentialManagementCluster::class;

    protected static ?string $modelLabel = 'Constancia';

    protected static ?string $pluralModelLabel = 'Constancias';

    protected static ?string $recordTitleAttribute = 'folio';

    protected static array $tableColumns = [
        [
            'name' => 'folio',
            'label' => 'Folio',
            'searchable' => true,
        ],
        [
            'name' => 'user.name',
            'label' => 'Titular',
            'searchable' => true,
        ],
        [
            'name' => 'activity.name',
            'label' => 'Actividad',
        ],
        [
            'name' => 'issued_at',
            'label' => 'Emisión',
            'type' => 'datetime',
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
            'index' => ManageCertificates::route('/'),
        ];
    }

    protected static function getFormFields(): array
    {
        return [
            [
                'name' => 'user_id',
                'label' => 'Titular',
                'type' => 'relation',
                'relationship' => 'user',
                'required' => true,
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
                'name' => 'certificate_template_id',
                'label' => 'Plantilla',
                'type' => 'relation',
                'relationship' => 'certificateTemplate',
            ],
            [
                'name' => 'folio',
                'label' => 'Folio',
                'required' => true,
            ],
            [
                'name' => 'certificate_type',
                'label' => 'Tipo de constancia',
            ],
            [
                'name' => 'issued_at',
                'label' => 'Fecha de emisión',
                'type' => 'datetime',
            ],
            [
                'name' => 'file_upload_id',
                'label' => 'Constancia o certificado',
                'type' => 'file',
                'directory' => 'certificates',
                'accepted_types' => ['application/pdf'],
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
