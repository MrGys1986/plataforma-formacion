<?php

namespace App\Filament\Resources\MicrocredentialResource\Pages;

use App\Filament\Resources\MicrocredentialResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageMicrocredentials extends ManageRecords
{
    protected static string $resource = MicrocredentialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
