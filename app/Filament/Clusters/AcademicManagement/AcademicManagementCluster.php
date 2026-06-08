<?php

namespace App\Filament\Clusters\AcademicManagement;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class AcademicManagementCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?string $navigationLabel = 'Gestión Académica';

    protected static ?string $title = 'Gestión Académica';

    protected static ?string $slug = 'gestion-academica';
}
