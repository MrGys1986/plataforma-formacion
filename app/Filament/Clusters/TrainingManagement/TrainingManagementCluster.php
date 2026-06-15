<?php

namespace App\Filament\Clusters\TrainingManagement;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class TrainingManagementCluster extends Cluster
{
    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $navigationLabel = 'Gestión de formación';

    protected static ?string $title = 'Gestión de formación';

    protected static ?string $slug = 'gestion-formacion';
}
