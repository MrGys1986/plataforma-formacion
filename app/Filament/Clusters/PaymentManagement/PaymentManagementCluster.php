<?php

namespace App\Filament\Clusters\PaymentManagement;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class PaymentManagementCluster extends Cluster
{
    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $navigationLabel = 'Pagos';

    protected static ?string $title = 'Pagos';

    protected static ?string $slug = 'pagos';
}
