<?php

namespace App\Filament\Clusters\ResourceManagement;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class ResourceManagementCluster extends Cluster
{
    protected static ?int $navigationSort = 6;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolderOpen;

    protected static ?string $navigationLabel = 'Recursos';

    protected static ?string $title = 'Recursos';

    protected static ?string $slug = 'recursos';
}
