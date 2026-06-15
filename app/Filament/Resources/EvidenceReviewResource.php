<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\TrainingManagement\TrainingManagementCluster;
use App\Filament\Resources\EvidenceReviewResource\Pages\ManageEvidenceReviews;
use App\Models\EvidenceReview;

class EvidenceReviewResource extends InstitutionalResource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 7;

    protected static ?string $model = EvidenceReview::class;

    protected static ?string $cluster = TrainingManagementCluster::class;

    protected static ?string $modelLabel = 'Revisión de evidencia';

    protected static ?string $pluralModelLabel = 'Revisiones de evidencias';

    protected static ?string $recordTitleAttribute = 'id';

    protected static array $formFields = [
        0 => [
            'name' => 'evidence_id',
            'label' => 'Evidencia',
            'type' => 'relation',
            'relationship' => 'evidence',
            'title' => 'title',
            'required' => true,
        ],
        1 => [
            'name' => 'previous_status',
            'label' => 'Estado anterior',
        ],
        2 => [
            'name' => 'new_status',
            'label' => 'Estado nuevo',
            'required' => true,
        ],
        3 => [
            'name' => 'observations',
            'label' => 'Observaciones',
            'type' => 'textarea',
        ],
        4 => [
            'name' => 'reviewed_at',
            'label' => 'Fecha de revisión',
            'type' => 'datetime',
        ],
    ];

    protected static array $tableColumns = [
        0 => [
            'name' => 'evidence.title',
            'label' => 'Evidencia',
        ],
        1 => [
            'name' => 'reviewedBy.name',
            'label' => 'Revisó',
        ],
        2 => [
            'name' => 'new_status',
            'label' => 'Estado',
        ],
        3 => [
            'name' => 'reviewed_at',
            'label' => 'Fecha',
            'type' => 'datetime',
        ],
    ];

    protected static ?string $statusColumn = 'new_status';

    protected static bool $readOnly = false;

    public static function getPages(): array
    {
        return [
            'index' => ManageEvidenceReviews::route('/'),
        ];
    }
}
