<?php

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Concerns\HasEditionPageContext;
use App\Filament\Resources\EnrollmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageEnrollments extends ManageRecords
{
    use HasEditionPageContext;

    protected static string $resource = EnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getEditionContextHeaderActions(),
            CreateAction::make(),
        ];
    }
}
