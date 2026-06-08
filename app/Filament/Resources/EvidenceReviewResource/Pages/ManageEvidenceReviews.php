<?php

namespace App\Filament\Resources\EvidenceReviewResource\Pages;

use App\Filament\Resources\EvidenceReviewResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageEvidenceReviews extends ManageRecords
{
    protected static string $resource = EvidenceReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
