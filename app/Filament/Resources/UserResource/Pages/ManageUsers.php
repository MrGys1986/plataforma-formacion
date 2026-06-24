<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Clusters\UserManagement\UserManagementCluster;
use App\Filament\Resources\UserResource;
use Filament\Actions\CreateAction;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Pages\ManageRecords;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    /**
     * @return array<NavigationItem>
     */
    public function getSubNavigation(): array
    {
        return UserManagementCluster::appendRoleNavigationItems(parent::getSubNavigation());
    }
}
