<?php

namespace App\Filament\Resources\CertificationProgramResource\Pages;

use App\Filament\Resources\CertificationProgramResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCertificationPrograms extends ManageRecords
{
    protected static string $resource = CertificationProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
