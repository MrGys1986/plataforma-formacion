<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\TrainingManagement\TrainingManagementCluster;
use App\Filament\Resources\EnrollmentResource\Pages\ManageEnrollments;
use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Builder;

class EnrollmentResource extends InstitutionalResource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 4;

    protected static ?string $model = Enrollment::class;

    protected static ?string $cluster = TrainingManagementCluster::class;

    protected static ?string $modelLabel = 'Inscripción';

    protected static ?string $pluralModelLabel = 'Inscripciones';

    protected static ?string $recordTitleAttribute = 'id';

    protected static array $tableColumns = [
        [
            'name' => 'user.name',
            'label' => 'Participante',
            'searchable' => true,
        ],
        [
            'name' => 'activity.name',
            'label' => 'Actividad',
            'searchable' => true,
        ],
        [
            'name' => 'status',
            'label' => 'Estado',
        ],
        [
            'name' => 'completion_status',
            'label' => 'Finalización',
        ],
    ];

    protected static ?string $statusColumn = 'status';

    protected static bool $readOnly = false;

    public static function getPages(): array
    {
        return [
            'index' => ManageEnrollments::route('/'),
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
                'required' => true,
                'default' => fn (): ?int => request()->filled('activity')
                    ? request()->integer('activity')
                    : null,
                'disabled' => fn (): bool => request()->filled('activity'),
                'dehydrated' => true,
            ],
            [
                'name' => 'status',
                'label' => 'Estado',
                'type' => 'select',
                'required' => true,
                'options' => [
                    'solicitada' => 'Pendiente de revisión',
                    'aprobada' => 'Aprobada',
                    'rechazada' => 'Rechazada',
                    'cancelada' => 'Cancelada',
                ],
            ],
            [
                'name' => 'payment_status',
                'label' => 'Estado de pago',
                'type' => 'select',
                'required' => true,
                'options' => [
                    'no_aplica' => 'No aplica',
                    'pendiente' => 'Pendiente de validación',
                    'aprobado' => 'Pago aprobado',
                    'rechazado' => 'Pago rechazado',
                ],
            ],
            [
                'name' => 'completion_status',
                'label' => 'Estado de finalización',
                'type' => 'select',
                'required' => true,
                'options' => [
                    'no_iniciado' => 'No iniciado',
                    'en_progreso' => 'En progreso',
                    'completado' => 'Completado',
                    'no_aprobado' => 'No aprobado',
                ],
            ],
            [
                'name' => 'final_score',
                'label' => 'Calificación final',
                'type' => 'number',
                'min' => 0,
                'max' => 100,
                'step' => 0.01,
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
