<?php

namespace App\Filament\Clusters\LearningPathManagement;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class LearningPathManagementCluster extends Cluster
{
    protected static ?int $navigationSort = 8;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;

    protected static ?string $navigationLabel = 'Rutas de aprendizaje';

    protected static ?string $title = 'Rutas de aprendizaje';

    protected static ?string $slug = 'rutas-aprendizaje';
}
