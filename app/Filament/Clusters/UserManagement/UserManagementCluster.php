<?php

namespace App\Filament\Clusters\UserManagement;

use App\Filament\Resources\UserResource;
use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Navigation\NavigationItem;
use Filament\Support\Icons\Heroicon;

class UserManagementCluster extends Cluster
{
    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $navigationLabel = 'Usuarios y áreas';

    protected static ?string $title = 'Usuarios y áreas';

    protected static ?string $slug = 'usuarios-areas';

    /**
     * @return array<NavigationItem>
     */
    public function getSubNavigation(): array
    {
        return static::appendRoleNavigationItems(parent::getSubNavigation());
    }

    /**
     * @param  array<NavigationItem>  $items
     * @return array<NavigationItem>
     */
    public static function appendRoleNavigationItems(array $items): array
    {
        foreach ([
            'superadministradores',
            'personal',
            'responsables_area',
            'evaluadores',
            'recursos_humanos',
            'calidad_academica',
            'educacion_continua',
            'profesores',
            'alumnos',
            'externos',
        ] as $configuration) {
            $items = [
                ...$items,
                ...UserResource::withConfiguration(
                    $configuration,
                    fn (): array => UserResource::getNavigationItems(),
                ),
            ];
        }

        return $items;
    }
}
