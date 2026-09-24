<?php

namespace App\Filament\Resources\TrainingProgramResource\Pages;

use App\Filament\Resources\TrainingProgramResource;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class ManageTrainingPrograms extends ManageRecords
{
    protected static string $resource = TrainingProgramResource::class;

    public function getHeading(): string|Htmlable|null
    {
        return view('filament.partials.resource-heading', [
            'icon' => Heroicon::OutlinedAcademicCap,
            'label' => 'Gestión de formación',
        ]);
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Administra en un solo catálogo los cursos, minicursos y talleres institucionales.';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
