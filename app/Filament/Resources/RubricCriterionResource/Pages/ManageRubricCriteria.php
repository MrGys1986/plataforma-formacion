<?php
namespace App\Filament\Resources\RubricCriterionResource\Pages;
use App\Filament\Resources\RubricCriterionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageRubricCriteria extends ManageRecords
{
    protected static string $resource = RubricCriterionResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
