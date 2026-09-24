<?php

namespace App\Filament\Resources\ActivityResource\Pages;

use App\Filament\Concerns\HasTrainingProgramPageContext;
use App\Filament\Resources\ActivityResource;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class ManageActivities extends ManageRecords
{
    use HasTrainingProgramPageContext;

    protected static string $resource = ActivityResource::class;

    public function getHeading(): string|Htmlable|null
    {
        return view('filament.partials.resource-heading', [
            'icon' => Heroicon::OutlinedRectangleStack,
            'label' => 'Cursos y ediciones',
        ]);
    }

    public function getSubheading(): string|Htmlable|null
    {
        if ($program = $this->getTrainingProgramContext()) {
            return collect([
                'Ediciones del programa seleccionado',
                $program->name,
                $program->activityType?->name,
            ])->filter()->implode(' · ');
        }

        return 'Consulta la operación de cursos, minicursos y talleres desde una sola tabla.';
    }

    protected function getHeaderActions(): array
    {
        return $this->getTrainingProgramContextHeaderActions();
    }
}
