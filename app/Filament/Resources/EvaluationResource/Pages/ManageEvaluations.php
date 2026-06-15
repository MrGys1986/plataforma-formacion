<?php

namespace App\Filament\Resources\EvaluationResource\Pages;

use App\Filament\Concerns\HasEditionPageContext;
use App\Filament\Resources\EvaluationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageEvaluations extends ManageRecords
{
    use HasEditionPageContext;

    protected static string $resource = EvaluationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getEditionContextHeaderActions(),
            CreateAction::make(),
        ];
    }
}
