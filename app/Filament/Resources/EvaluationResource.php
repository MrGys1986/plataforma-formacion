<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\TrainingManagement\TrainingManagementCluster;
use App\Filament\Resources\EvaluationResource\Pages\ManageEvaluations;
use App\Models\Evaluation;
use Illuminate\Database\Eloquent\Builder;

class EvaluationResource extends InstitutionalResource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 8;

    protected static ?string $model = Evaluation::class;

    protected static ?string $cluster = TrainingManagementCluster::class;

    protected static ?string $modelLabel = 'Evaluación';

    protected static ?string $pluralModelLabel = 'Evaluaciones';

    protected static ?string $recordTitleAttribute = 'name';

    protected static array $tableColumns = [
        [
            'name' => 'name',
            'label' => 'Nombre',
            'searchable' => true,
        ],
        [
            'name' => 'activity.name',
            'label' => 'Actividad',
        ],
        [
            'name' => 'evaluation_type',
            'label' => 'Tipo',
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
            'index' => ManageEvaluations::route('/'),
        ];
    }

    protected static function getFormFields(): array
    {
        return [
            [
                'name' => 'activity_id',
                'label' => 'Actividad',
                'type' => 'relation',
                'relationship' => 'activity',
                'required' => true,
                'default' => fn (): ?int => request()->filled('activity')
                    ? request()->integer('activity')
                    : null,
                'disabled' => fn (): bool => request()->filled('activity'),
                'dehydrated' => true,
            ],
            [
                'name' => 'name',
                'label' => 'Nombre',
                'required' => true,
            ],
            [
                'name' => 'description',
                'label' => 'Descripción',
                'type' => 'textarea',
            ],
            [
                'name' => 'evaluation_type',
                'label' => 'Tipo de evaluación',
            ],
            [
                'name' => 'minimum_score',
                'label' => 'Calificación mínima',
                'type' => 'number',
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
