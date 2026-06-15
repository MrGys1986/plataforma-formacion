<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\TrainingManagement\TrainingManagementCluster;
use App\Filament\Resources\EvidenceResource\Pages\ManageEvidences;
use App\Models\Evidence;
use Illuminate\Database\Eloquent\Builder;

class EvidenceResource extends InstitutionalResource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 6;

    protected static ?string $model = Evidence::class;

    protected static ?string $cluster = TrainingManagementCluster::class;

    protected static ?string $modelLabel = 'Evidencia';

    protected static ?string $pluralModelLabel = 'Evidencias';

    protected static ?string $recordTitleAttribute = 'title';

    protected static array $tableColumns = [
        [
            'name' => 'title',
            'label' => 'Título',
            'searchable' => true,
        ],
        [
            'name' => 'user.name',
            'label' => 'Participante',
        ],
        [
            'name' => 'activity.name',
            'label' => 'Actividad',
        ],
        [
            'name' => 'assignedEvaluator.name',
            'label' => 'Evaluador',
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
            'index' => ManageEvidences::route('/'),
        ];
    }

    protected static function getFormFields(): array
    {
        return [
            [
                'name' => 'user_id',
                'label' => 'Participante',
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
                'name' => 'assigned_evaluator_id',
                'label' => 'Evaluador asignado',
                'type' => 'relation',
                'relationship' => 'assignedEvaluator',
                'role' => 'Evaluador',
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
                'name' => 'evidence_type',
                'label' => 'Tipo de evidencia',
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
