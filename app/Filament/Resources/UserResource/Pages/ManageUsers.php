<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        if (UserResource::getConfiguration()?->getKey()) {
            return [
                'activos' => Tab::make('Activos')
                    ->query(fn (Builder $query): Builder => $query->where('status', 'activo')),
                'inactivos' => Tab::make('Inactivos')
                    ->query(fn (Builder $query): Builder => $query->where('status', '!=', 'activo')),
            ];
        }

        return [
            'todos' => Tab::make('Todos'),
            'superadministradores' => $this->roleTab('Superadministrador'),
            'personal' => $this->roleTab('Personal'),
            'responsables_area' => $this->roleTab('Responsable Area'),
            'evaluadores' => $this->roleTab('Evaluador'),
            'recursos_humanos' => $this->roleTab('Recursos Humanos'),
            'calidad' => $this->roleTab('Calidad Academica'),
            'educacion_continua' => $this->roleTab('Educacion Continua'),
            'profesores' => $this->roleTab('Profesor'),
            'alumnos' => $this->roleTab('Alumno'),
            'externos' => $this->roleTab('Externo'),
            'inactivos' => Tab::make('Inactivos')
                ->query(fn (Builder $query): Builder => $query->where('status', '!=', 'activo')),
        ];
    }

    private function roleTab(string $role): Tab
    {
        return Tab::make($role)
            ->query(fn (Builder $query): Builder => $query->whereHas(
                'roles',
                fn (Builder $roles): Builder => $roles->where('name', $role),
            ));
    }
}
