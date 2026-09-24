<?php

namespace App\Filament\Resources\AreaResource\Pages;

use App\Filament\Resources\AreaResource;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class ManageAreas extends ManageRecords
{
    protected static string $resource = AreaResource::class;

    public function getHeading(): string|Htmlable|null
    {
        return view('filament.partials.resource-heading', [
            'icon' => Heroicon::OutlinedBuildingOffice2,
            'label' => 'Áreas',
        ]);
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Organiza las áreas institucionales y asigna claramente a sus responsables.';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
