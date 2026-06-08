<?php

namespace App\Filament\Clusters\ContinuingEducation;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class ContinuingEducationCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?string $navigationLabel = 'Educación Continua';

    protected static ?string $title = 'Educación Continua';

    protected static ?string $slug = 'educacion-continua';
}
