<?php

namespace App\Filament\Resources\DiplomaProgramResource\Pages;

use App\Filament\Resources\DiplomaProgramResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageDiplomaPrograms extends ManageRecords
{
    protected static string $resource = DiplomaProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
