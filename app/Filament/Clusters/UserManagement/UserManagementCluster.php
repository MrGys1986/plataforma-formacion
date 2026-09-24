<?php

namespace App\Filament\Clusters\UserManagement;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class UserManagementCluster extends Cluster
{
    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $navigationLabel = 'Usuarios y áreas';

    protected static ?string $title = 'Usuarios y áreas';

    protected static ?string $slug = 'usuarios-areas';
}
