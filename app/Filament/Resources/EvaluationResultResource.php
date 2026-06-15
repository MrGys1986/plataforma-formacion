<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\TrainingManagement\TrainingManagementCluster;
use App\Filament\Resources\EvaluationResultResource\Pages\ManageEvaluationResults;
use App\Models\EvaluationResult;

class EvaluationResultResource extends InstitutionalResource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 9;

    protected static ?string $model = EvaluationResult::class;

    protected static ?string $cluster = TrainingManagementCluster::class;

    protected static ?string $modelLabel = 'Resultado de evaluación';

    protected static ?string $pluralModelLabel = 'Resultados de evaluaciones';

    protected static ?string $recordTitleAttribute = 'id';

    protected static array $formFields = [
        0 => [
            'name' => 'evaluation_id',
            'label' => 'Evaluación',
            'type' => 'relation',
            'relationship' => 'evaluation',
            'required' => true,
        ],
        1 => [
            'name' => 'user_id',
            'label' => 'Participante',
            'type' => 'relation',
            'relationship' => 'user',
            'required' => true,
        ],
        2 => [
            'name' => 'score',
            'label' => 'Calificación',
            'type' => 'number',
        ],
        3 => [
            'name' => 'result',
            'label' => 'Resultado',
        ],
        4 => [
            'name' => 'observations',
            'label' => 'Observaciones',
            'type' => 'textarea',
        ],
        5 => [
            'name' => 'evaluated_at',
            'label' => 'Fecha de evaluación',
            'type' => 'datetime',
        ],
    ];

    protected static array $tableColumns = [
        0 => [
            'name' => 'evaluation.name',
            'label' => 'Evaluación',
        ],
        1 => [
            'name' => 'user.name',
            'label' => 'Participante',
        ],
        2 => [
            'name' => 'score',
            'label' => 'Calificación',
        ],
        3 => [
            'name' => 'result',
            'label' => 'Resultado',
        ],
    ];

    protected static ?string $statusColumn = 'result';

    protected static bool $readOnly = false;

    public static function getPages(): array
    {
        return [
            'index' => ManageEvaluationResults::route('/'),
        ];
    }
}
