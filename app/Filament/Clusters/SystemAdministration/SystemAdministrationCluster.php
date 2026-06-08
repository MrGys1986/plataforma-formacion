<?php

namespace App\Filament\Clusters\SystemAdministration;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class SystemAdministrationCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?string $navigationLabel = 'Administración del Sistema';

    protected static ?string $title = 'Administración del Sistema';

    protected static ?string $slug = 'administracion-sistema';
}
