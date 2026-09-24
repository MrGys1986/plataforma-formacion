<?php

namespace App\Filament\Resources\SurveyResponseResource\Pages;

use App\Filament\Concerns\HasEditionPageContext;
use App\Filament\Resources\SurveyResponseResource;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class ManageSurveyResponses extends ManageRecords
{
    use HasEditionPageContext;

    protected static string $resource = SurveyResponseResource::class;

    public function getHeading(): string|Htmlable|null
    {
        return view('filament.partials.resource-heading', [
            'icon' => Heroicon::OutlinedChatBubbleLeftRight,
            'label' => 'Encuesta de satisfacción',
        ]);
    }

    protected function getHeaderActions(): array
    {
        return $this->getEditionContextHeaderActions();
    }
}
