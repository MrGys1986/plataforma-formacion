<?php

namespace App\Filament\Resources\DigitalResourceResource\Pages;

use App\Filament\Concerns\HasEditionPageContext;
use App\Filament\Resources\DigitalResourceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageDigitalResources extends ManageRecords
{
    use HasEditionPageContext;

    protected static string $resource = DigitalResourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getEditionContextHeaderActions(),
            CreateAction::make(),
        ];
    }
}
