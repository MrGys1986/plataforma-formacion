<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\EvidenceManagement\EvidenceManagementCluster;
use App\Filament\Resources\EvaluationResource\Pages\ManageEvaluations;
use App\Models\Evaluation;

class EvaluationResource extends InstitutionalResource
{
    protected static ?string $model = Evaluation::class;

    protected static ?string $cluster = EvidenceManagementCluster::class;

    protected static ?string $modelLabel = 'Evaluación';

    protected static ?string $pluralModelLabel = 'Evaluaciones';

    protected static ?string $recordTitleAttribute = 'name';

    protected static array $formFields = [
        0 => [
            'name' => 'activity_id',
            'label' => 'Actividad',
            'type' => 'relation',
            'relationship' => 'activity',
            'required' => true,
        ],
        1 => [
            'name' => 'name',
            'label' => 'Nombre',
            'required' => true,
        ],
        2 => [
            'name' => 'description',
            'label' => 'Descripción',
            'type' => 'textarea',
        ],
        3 => [
            'name' => 'evaluation_type',
            'label' => 'Tipo de evaluación',
        ],
        4 => [
            'name' => 'minimum_score',
            'label' => 'Calificación mínima',
            'type' => 'number',
        ],
        5 => [
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
            'name' => 'activity.name',
            'label' => 'Actividad',
        ],
        2 => [
            'name' => 'evaluation_type',
            'label' => 'Tipo',
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
            'index' => ManageEvaluations::route('/'),
        ];
    }
}
