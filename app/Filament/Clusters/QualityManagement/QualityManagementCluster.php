<?php

namespace App\Filament\Clusters\QualityManagement;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class QualityManagementCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?string $navigationLabel = 'Calidad y Auditoría';

    protected static ?string $title = 'Calidad y Auditoría';

    protected static ?string $slug = 'calidad-auditoria';
}
