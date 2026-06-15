<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\TrainingManagement\TrainingManagementCluster;
use App\Filament\Resources\AttendanceRecordResource\Pages\ManageAttendanceRecords;
use App\Models\AttendanceRecord;
use Illuminate\Database\Eloquent\Builder;

class AttendanceRecordResource extends InstitutionalResource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 5;

    protected static ?string $model = AttendanceRecord::class;

    protected static ?string $cluster = TrainingManagementCluster::class;

    protected static ?string $modelLabel = 'Registro de asistencia';

    protected static ?string $pluralModelLabel = 'Registros de asistencia';

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
        ],
        [
            'name' => 'session_date',
            'label' => 'Fecha',
            'type' => 'date',
        ],
        [
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

    protected static function getFormFields(): array
    {
        return [
            [
                'name' => 'enrollment_id',
                'label' => 'Inscripción',
                'type' => 'relation',
                'relationship' => 'enrollment',
                'title' => 'id',
                'required' => true,
                'modify_query_using' => fn (Builder $query): Builder => request()->filled('activity')
                    ? $query->where('activity_id', request()->integer('activity'))
                    : $query,
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
                'name' => 'user_id',
                'label' => 'Participante',
                'type' => 'relation',
                'relationship' => 'user',
                'required' => true,
            ],
            [
                'name' => 'session_date',
                'label' => 'Fecha',
                'type' => 'date',
                'required' => true,
            ],
            [
                'name' => 'attended',
                'label' => 'Asistió',
                'type' => 'toggle',
            ],
            [
                'name' => 'observations',
                'label' => 'Observaciones',
                'type' => 'textarea',
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
