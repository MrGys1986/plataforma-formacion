<?php

namespace App\Filament\Resources\ActivityTypeResource\Pages;

use App\Filament\Resources\ActivityTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageActivityTypes extends ManageRecords
{
    protected static string $resource = ActivityTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
