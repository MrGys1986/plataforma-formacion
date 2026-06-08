<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\AcademicManagement\AcademicManagementCluster;
use App\Filament\Resources\EnrollmentResource\Pages\ManageEnrollments;
use App\Models\Enrollment;

class EnrollmentResource extends InstitutionalResource
{
    protected static ?string $model = Enrollment::class;

    protected static ?string $cluster = AcademicManagementCluster::class;

    protected static ?string $modelLabel = 'Inscripción';

    protected static ?string $pluralModelLabel = 'Inscripciones';

    protected static ?string $recordTitleAttribute = 'id';

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
            'required' => true,
        ],
        2 => [
            'name' => 'status',
            'label' => 'Estado',
        ],
        3 => [
            'name' => 'payment_status',
            'label' => 'Estado de pago',
        ],
        4 => [
            'name' => 'completion_status',
            'label' => 'Estado de finalización',
        ],
        5 => [
            'name' => 'final_score',
            'label' => 'Calificación final',
            'type' => 'number',
        ],
    ];

    protected static array $tableColumns = [
        0 => [
            'name' => 'user.name',
            'label' => 'Participante',
            'searchable' => true,
        ],
        1 => [
            'name' => 'activity.name',
            'label' => 'Actividad',
            'searchable' => true,
        ],
        2 => [
            'name' => 'status',
            'label' => 'Estado',
        ],
        3 => [
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
}
