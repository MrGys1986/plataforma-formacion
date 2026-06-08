<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\AcademicManagement\AcademicManagementCluster;
use App\Filament\Resources\ActivityResource\Pages\ManageActivities;
use App\Models\Activity;

class ActivityResource extends InstitutionalResource
{
    protected static ?string $model = Activity::class;

    protected static ?string $cluster = AcademicManagementCluster::class;

    protected static ?string $modelLabel = 'Actividad';

    protected static ?string $pluralModelLabel = 'Actividades';

    protected static ?string $recordTitleAttribute = 'name';

    protected static array $formFields = [
        0 => [
            'name' => 'activity_type_id',
            'label' => 'Tipo',
            'type' => 'relation',
            'relationship' => 'activityType',
            'required' => true,
        ],
        1 => [
            'name' => 'area_id',
            'label' => 'Área',
            'type' => 'relation',
            'relationship' => 'area',
        ],
        2 => [
            'name' => 'instructor_id',
            'label' => 'Instructor',
            'type' => 'relation',
            'relationship' => 'instructor',
        ],
        3 => [
            'name' => 'name',
            'label' => 'Nombre',
            'required' => true,
        ],
        4 => [
            'name' => 'slug',
            'label' => 'Identificador',
            'required' => true,
        ],
        5 => [
            'name' => 'description',
            'label' => 'Descripción',
            'type' => 'textarea',
        ],
        6 => [
            'name' => 'duration_hours',
            'label' => 'Duración en horas',
            'type' => 'number',
            'required' => true,
        ],
        7 => [
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
            'name' => 'activityType.name',
            'label' => 'Tipo',
        ],
        2 => [
            'name' => 'area.name',
            'label' => 'Área',
        ],
        3 => [
            'name' => 'start_date',
            'label' => 'Inicio',
            'type' => 'date',
        ],
        4 => [
            'name' => 'status',
            'label' => 'Estado',
        ],
    ];

    protected static ?string $statusColumn = 'status';

    protected static bool $readOnly = false;

    public static function getPages(): array
    {
        return [
            'index' => ManageActivities::route('/'),
        ];
    }
}
