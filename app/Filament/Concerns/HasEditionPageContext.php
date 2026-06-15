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

        $edition = filled($activity->edition_code)
            ? $activity->edition_code
            : 'Edición '.$activity->edition_number;

        return collect([
            "Gestionando {$edition}",
            $activity->trainingProgram?->name,
            $activity->trainingProgram?->activityType?->name,
        ])->filter()->implode(' · ');
    }

    protected function getEditionContextHeaderActions(): array
    {
        if (! $activity = $this->getActivityContext()) {
            return [];
        }

        return [
            Action::make('volver_edicion')
                ->label('Volver a la edición')
                ->icon(Heroicon::OutlinedArrowLeft)
                ->color('gray')
                ->url(EditionControlPage::getUrl(['record' => $activity])),
        ];
    }
}
