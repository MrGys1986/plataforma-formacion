<?php

namespace App\Filament\Resources\FileUploadResource\Pages;

use App\Filament\Resources\FileUploadResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageFileUploads extends ManageRecords
{
    protected static string $resource = FileUploadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
