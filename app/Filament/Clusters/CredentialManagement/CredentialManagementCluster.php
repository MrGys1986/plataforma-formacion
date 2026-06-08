<?php

namespace App\Filament\Clusters\CredentialManagement;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class CredentialManagementCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?string $navigationLabel = 'Constancias y Microcredenciales';

    protected static ?string $title = 'Constancias y Microcredenciales';

    protected static ?string $slug = 'constancias-microcredenciales';
}
