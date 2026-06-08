<?php

namespace App\Filament\Clusters\EvidenceManagement;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class EvidenceManagementCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?string $navigationLabel = 'Evidencias y Evaluación';

    protected static ?string $title = 'Evidencias y Evaluación';

    protected static ?string $slug = 'evidencias-evaluacion';
}
