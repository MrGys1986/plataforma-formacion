<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\AuditManagement\AuditManagementCluster;
use App\Filament\Resources\FailedJobResource\Pages\ManageFailedJobs;
use App\Models\FailedJob;

class FailedJobResource extends InstitutionalResource
{
    protected static ?int $navigationSort = 3;

    protected static ?string $model = FailedJob::class;

    protected static ?string $cluster = AuditManagementCluster::class;

    protected static ?string $modelLabel = 'Fallo de proceso';

    protected static ?string $pluralModelLabel = 'Fallos del sistema';

    protected static ?string $recordTitleAttribute = 'uuid';

    protected static ?string $statusColumn = null;

    protected static bool $readOnly = true;

    protected static array $formFields = [];

    protected static array $tableColumns = [
        ['name' => 'uuid', 'label' => 'Identificador', 'searchable' => true],
        ['name' => 'connection', 'label' => 'Conexión'],
        ['name' => 'queue', 'label' => 'Cola'],
        ['name' => 'exception', 'label' => 'Error', 'searchable' => true],
        ['name' => 'failed_at', 'label' => 'Fecha', 'type' => 'datetime'],
    ];

    public static function getPages(): array
    {
        return ['index' => ManageFailedJobs::route('/')];
    }
}
