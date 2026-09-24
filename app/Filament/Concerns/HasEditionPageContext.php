<?php

namespace App\Filament\Concerns;

use App\Filament\Pages\EditionControlPage;
use App\Filament\Pages\ProgramControlPage;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;

trait HasEditionPageContext
{
    use ResolvesTrainingContext;

    public function getSubNavigation(): array
    {
        if (request()->filled('training_program')) {
            return [];
        }

        return parent::getSubNavigation();
    }

    public function getSubheading(): ?string
    {
        if ($program = $this->getTrainingProgramContext()) {
            return collect([
                'Gestionando '.$program->name,
                $program->activityType?->name,
            ])->filter()->implode(' · ');
        }

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
        if ($program = $this->getTrainingProgramContext()) {
            return [
                Action::make('volver_programa')
                    ->label('Volver al programa')
                    ->icon(Heroicon::OutlinedArrowLeft)
                    ->color('gray')
                    ->url(ProgramControlPage::getUrl(['record' => $program->getKey()])),
            ];
        }

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
