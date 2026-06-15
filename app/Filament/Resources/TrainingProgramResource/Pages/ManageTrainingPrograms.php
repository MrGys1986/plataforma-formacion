<?php

namespace App\Filament\Resources\TrainingProgramResource\Pages;

use App\Filament\Resources\TrainingProgramResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ManageTrainingPrograms extends ManageRecords
{
    protected static string $resource = TrainingProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        if (TrainingProgramResource::getConfiguration()?->getKey()) {
            return [
                'activos' => Tab::make('Activos')
                    ->query(fn (Builder $query): Builder => $query->where('status', 'activo')),
                'inactivos' => Tab::make('Inactivos')
                    ->query(fn (Builder $query): Builder => $query->where('status', 'inactivo')),
            ];
        }

        return [
            'todos' => Tab::make('Todos'),
            'cursos' => $this->typeTab('Cursos', 'Curso'),
            'minicursos' => $this->typeTab('Minicursos', 'Minicurso'),
            'talleres' => $this->typeTab('Talleres', 'Taller'),
            'inactivos' => Tab::make('Inactivos')
                ->query(fn (Builder $query): Builder => $query->where('status', 'inactivo')),
        ];
    }

    private function typeTab(string $label, string $type): Tab
    {
        return Tab::make($label)
            ->query(fn (Builder $query): Builder => $query->whereHas(
                'activityType',
                fn (Builder $activityTypes): Builder => $activityTypes->where('name', $type),
            ));
    }
}
