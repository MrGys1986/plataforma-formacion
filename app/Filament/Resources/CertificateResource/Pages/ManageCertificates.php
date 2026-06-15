<?php

namespace App\Filament\Resources\CertificateResource\Pages;

use App\Filament\Concerns\HasEditionPageContext;
use App\Filament\Resources\CertificateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCertificates extends ManageRecords
{
    use HasEditionPageContext;

    protected static string $resource = CertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getEditionContextHeaderActions(),
            CreateAction::make(),
        ];
    }
}
