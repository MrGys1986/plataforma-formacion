<?php

namespace App\Filament\Clusters\AuditManagement;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class AuditManagementCluster extends Cluster
{
    protected static ?int $navigationSort = 5;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static ?string $navigationLabel = 'Auditoría y trazabilidad';

    protected static ?string $title = 'Auditoría y trazabilidad';

    protected static ?string $slug = 'auditoria-trazabilidad';
}
