<?php

namespace App\Filament\Resources\AreaResource\Pages;

use App\Filament\Clusters\UserManagement\UserManagementCluster;
use App\Filament\Resources\AreaResource;
use Filament\Actions\CreateAction;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Pages\ManageRecords;

class ManageAreas extends ManageRecords
{
    protected static string $resource = AreaResource::class;

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
