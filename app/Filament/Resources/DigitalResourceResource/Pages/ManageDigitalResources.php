<?php

namespace App\Filament\Resources\DigitalResourceResource\Pages;

use App\Filament\Resources\DigitalResourceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageDigitalResources extends ManageRecords
{
    protected static string $resource = DigitalResourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
