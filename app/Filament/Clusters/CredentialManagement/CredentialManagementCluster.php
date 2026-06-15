<?php

namespace App\Filament\Clusters\CredentialManagement;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class CredentialManagementCluster extends Cluster
{
    protected static ?int $navigationSort = 7;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    protected static ?string $navigationLabel = 'Microcredenciales';

    protected static ?string $title = 'Microcredenciales';

    protected static ?string $slug = 'microcredenciales-constancias';
}
