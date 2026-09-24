<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;

    public function getHeading(): string|Htmlable|null
    {
        return view('filament.partials.resource-heading', [
            'icon' => Heroicon::OutlinedUsers,
            'label' => 'Usuarios',
        ]);
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Administra cuentas, roles, perfiles y adscripción institucional desde un solo lugar.';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
