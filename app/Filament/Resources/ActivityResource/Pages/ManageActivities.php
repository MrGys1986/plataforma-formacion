<?php

namespace App\Filament\Resources\ActivityResource\Pages;

use App\Filament\Concerns\HasTrainingProgramPageContext;
use App\Filament\Resources\ActivityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ManageActivities extends ManageRecords
{
    use HasTrainingProgramPageContext;

    protected static string $resource = ActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getTrainingProgramContextHeaderActions(),
            CreateAction::make()
                ->label('Nueva edición'),
        ];
    }

    public function getTabs(): array
    {
        if ($this->getTrainingProgramContext()) {
            return [
                'todas' => Tab::make('Todas'),
                'en_operacion' => Tab::make('En operación')
                    ->query(fn (Builder $query): Builder => $query->whereIn(
                        'status',
                        ['publicado', 'en_inscripcion', 'cupo_lleno', 'en_curso'],
                    )),
                'cerradas' => Tab::make('Cerradas')
                    ->query(fn (Builder $query): Builder => $query->whereIn(
                        'status',
                        ['finalizado', 'cancelado', 'archivado'],
                    )),
            ];
        }

        return [
            'todas' => Tab::make('Todas'),
            'cursos' => $this->typeTab('Cursos', 'Curso'),
            'minicursos' => $this->typeTab('Minicursos', 'Minicurso'),
            'talleres' => $this->typeTab('Talleres', 'Taller'),
            'en_operacion' => Tab::make('En operación')
                ->query(fn (Builder $query): Builder => $query->whereIn(
                    'status',
                    ['publicado', 'en_inscripcion', 'cupo_lleno', 'en_curso'],
                )),
            'cerradas' => Tab::make('Cerradas')
                ->query(fn (Builder $query): Builder => $query->whereIn(
                    'status',
                    ['finalizado', 'cancelado', 'archivado'],
                )),
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
