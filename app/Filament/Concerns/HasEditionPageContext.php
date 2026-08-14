<?php

namespace App\Filament\Concerns;

use App\Filament\Pages\EditionControlPage;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;

trait HasEditionPageContext
{
    use ResolvesTrainingContext;

    public function getSubheading(): ?string
    {
        if (! $activity = $this->getActivityContext()) {
            return parent::getSubheading();
        }

        return collect([
            'Gestionando '.$activity->name,
            $activity->activityType?->name,
        ])->filter()->implode(' · ');
    }

    protected function getEditionContextHeaderActions(): array
    {
        if (! $activity = $this->getActivityContext()) {
            return [];
        }

        return [
            Action::make('volver_actividad')
                ->label('Volver a la actividad')
                ->icon(Heroicon::OutlinedArrowLeft)
                ->color('gray')
                ->url(EditionControlPage::getUrl(['record' => $activity->getKey()])),
        ];
    }
}
