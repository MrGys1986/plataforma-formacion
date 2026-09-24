<?php

namespace App\Filament\Resources\SurveyQuestionResource\Pages;

use App\Filament\Resources\SurveyQuestionResource;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class ManageSurveyQuestions extends ManageRecords
{
    protected static string $resource = SurveyQuestionResource::class;

    public function getHeading(): string|Htmlable|null
    {
        return view('filament.partials.resource-heading', [
            'icon' => Heroicon::OutlinedQuestionMarkCircle,
            'label' => 'Preguntas de satisfacción',
        ]);
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Configura una sola encuesta institucional para aplicarla de manera uniforme en todos los cursos.';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
