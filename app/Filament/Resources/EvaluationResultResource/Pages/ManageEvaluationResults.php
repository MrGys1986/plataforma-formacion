<?php

namespace App\Filament\Resources\EvaluationResultResource\Pages;

use App\Filament\Resources\EvaluationResultResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageEvaluationResults extends ManageRecords
{
    protected static string $resource = EvaluationResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
