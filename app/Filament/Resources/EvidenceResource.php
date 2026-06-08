<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\EvidenceManagement\EvidenceManagementCluster;
use App\Filament\Resources\EvidenceResource\Pages\ManageEvidences;
use App\Models\Evidence;

class EvidenceResource extends InstitutionalResource
{
    protected static ?string $model = Evidence::class;

    protected static ?string $cluster = EvidenceManagementCluster::class;

    protected static ?string $modelLabel = 'Evidencia';

    protected static ?string $pluralModelLabel = 'Evidencias';

    protected static ?string $recordTitleAttribute = 'title';

    protected static array $formFields = [
        0 => [
            'name' => 'user_id',
            'label' => 'Participante',
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
        6 => [
            'name' => 'assigned_evaluator_id',
            'label' => 'Evaluador asignado',
            'type' => 'relation',
            'relationship' => 'assignedEvaluator',
            'role' => 'Evaluador',
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
            'name' => 'evidence_type',
            'label' => 'Tipo de evidencia',
        ],
        5 => [
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
            'name' => 'user.name',
            'label' => 'Participante',
        ],
        2 => [
            'name' => 'activity.name',
            'label' => 'Actividad',
        ],
        4 => [
            'name' => 'assignedEvaluator.name',
            'label' => 'Evaluador',
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
            'index' => ManageEvidences::route('/'),
        ];
    }
}
