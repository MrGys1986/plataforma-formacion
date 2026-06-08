<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\AcademicManagement\AcademicManagementCluster;
use App\Filament\Resources\AttendanceRecordResource\Pages\ManageAttendanceRecords;
use App\Models\AttendanceRecord;

class AttendanceRecordResource extends InstitutionalResource
{
    protected static ?string $model = AttendanceRecord::class;

    protected static ?string $cluster = AcademicManagementCluster::class;

    protected static ?string $modelLabel = 'Registro de asistencia';

    protected static ?string $pluralModelLabel = 'Registros de asistencia';

    protected static ?string $recordTitleAttribute = 'id';

    protected static array $formFields = [
        0 => [
            'name' => 'enrollment_id',
            'label' => 'Inscripción',
            'type' => 'relation',
            'relationship' => 'enrollment',
            'title' => 'id',
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
            'name' => 'user_id',
            'label' => 'Participante',
            'type' => 'relation',
            'relationship' => 'user',
            'required' => true,
        ],
        3 => [
            'name' => 'session_date',
            'label' => 'Fecha',
            'type' => 'date',
            'required' => true,
        ],
        4 => [
            'name' => 'attended',
            'label' => 'Asistió',
            'type' => 'toggle',
        ],
        5 => [
            'name' => 'observations',
            'label' => 'Observaciones',
            'type' => 'textarea',
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
        ],
        2 => [
            'name' => 'session_date',
            'label' => 'Fecha',
            'type' => 'date',
        ],
        3 => [
            'name' => 'attended',
            'label' => 'Asistió',
            'type' => 'boolean',
        ],
    ];

    protected static ?string $statusColumn = null;

    protected static bool $readOnly = false;

    public static function getPages(): array
    {
        return [
            'index' => ManageAttendanceRecords::route('/'),
        ];
    }
}
