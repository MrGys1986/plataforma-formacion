<?php

namespace App\Filament\Concerns;

use App\Filament\Resources\TrainingProgramResource;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;

trait HasTrainingProgramPageContext
{
    use ResolvesTrainingContext;

    public function getSubheading(): ?string
    {
        if (! $program = $this->getTrainingProgramContext()) {
            return parent::getSubheading();
        }

        return collect([
            'Ediciones del programa seleccionado',
            $program->name,
            $program->activityType?->name,
        ])->filter()->implode(' · ');
    }

    protected function getTrainingProgramContextHeaderActions(): array
    {
        if (! $program = $this->getTrainingProgramContext()) {
            return [];
        }

        return [
            Action::make('volver_programas')
                ->label('Volver a programas')
                ->icon(Heroicon::OutlinedArrowLeft)
                ->color('gray')
                ->url(TrainingProgramResource::getBrowseUrlForProgram($program)),
        ];
    }
}
