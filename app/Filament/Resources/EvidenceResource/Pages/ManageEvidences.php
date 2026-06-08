<?php

namespace App\Filament\Resources\EvidenceResource\Pages;

use App\Filament\Resources\EvidenceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageEvidences extends ManageRecords
{
    protected static string $resource = EvidenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
