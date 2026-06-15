<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\AuditManagement\AuditManagementCluster;
use App\Filament\Resources\AuditLogResource\Pages\ManageAuditLogs;
use App\Models\AuditLog;

class AuditLogResource extends InstitutionalResource
{
    protected static ?int $navigationSort = 1;

    protected static ?string $model = AuditLog::class;

    protected static ?string $cluster = AuditManagementCluster::class;

    protected static ?string $modelLabel = 'Registro de auditoría';

    protected static ?string $pluralModelLabel = 'Registros de auditoría';

    protected static ?string $recordTitleAttribute = 'action';

    protected static array $formFields = [
    ];

    protected static array $tableColumns = [
        0 => [
            'name' => 'module',
            'label' => 'Módulo',
            'searchable' => true,
        ],
        1 => [
            'name' => 'action',
            'label' => 'Acción',
            'searchable' => true,
        ],
        2 => [
            'name' => 'user.name',
            'label' => 'Usuario',
        ],
        3 => [
            'name' => 'entity_type',
            'label' => 'Entidad',
        ],
        4 => [
            'name' => 'created_at',
            'label' => 'Fecha',
            'type' => 'datetime',
        ],
    ];

    protected static ?string $statusColumn = null;

    protected static bool $readOnly = true;

    public static function getPages(): array
    {
        return [
            'index' => ManageAuditLogs::route('/'),
        ];
    }
}
