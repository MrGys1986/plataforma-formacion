<?php
namespace App\Filament\Resources\RubricResource\Pages;
use App\Filament\Resources\RubricResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageRubrics extends ManageRecords
{
    protected static string $resource = RubricResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
