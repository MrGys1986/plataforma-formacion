<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\LearningPathManagement\LearningPathManagementCluster;
use App\Filament\Resources\DiplomaProgramResource\Pages\ManageDiplomaPrograms;
use App\Models\DiplomaProgram;
use Illuminate\Database\Eloquent\Builder;

class DiplomaProgramResource extends InstitutionalResource
{
    protected static ?int $navigationSort = 4;

    protected static ?string $model = DiplomaProgram::class;

    protected static ?string $cluster = LearningPathManagementCluster::class;

    protected static ?string $modelLabel = 'Diplomado';

    protected static ?string $pluralModelLabel = 'Diplomados';

    protected static ?string $recordTitleAttribute = 'name';

    protected static bool $softDeletes = true;

    protected static array $formFields = [
        ['name' => 'area_id', 'label' => 'Área', 'type' => 'relation', 'relationship' => 'area'],
        ['name' => 'name', 'label' => 'Nombre', 'required' => true],
        ['name' => 'slug', 'label' => 'Identificador', 'required' => true],
        ['name' => 'description', 'label' => 'Descripción', 'type' => 'textarea'],
        ['name' => 'objective', 'label' => 'Objetivo', 'type' => 'textarea'],
        ['name' => 'completion_criteria', 'label' => 'Criterios de logro', 'type' => 'textarea'],
        ['name' => 'total_hours', 'label' => 'Horas totales', 'type' => 'number'],
        [
            'name' => 'trainingPrograms',
            'label' => 'Cursos, minicursos y talleres requeridos',
            'type' => 'relation',
            'relationship' => 'trainingPrograms',
            'multiple' => true,
        ],
        [
            'name' => 'status',
            'label' => 'Estado',
            'type' => 'select',
            'options' => ['activo' => 'Activo', 'inactivo' => 'Inactivo', 'borrador' => 'Borrador'],
        ],
    ];

    protected static array $tableColumns = [
        ['name' => 'name', 'label' => 'Diplomado', 'searchable' => true],
        ['name' => 'area.name', 'label' => 'Área'],
        ['name' => 'training_programs_count', 'label' => 'Programas'],
        ['name' => 'total_hours', 'label' => 'Horas'],
        ['name' => 'status', 'label' => 'Estado'],
    ];

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount('trainingPrograms');
    }

    public static function getPages(): array
    {
        return ['index' => ManageDiplomaPrograms::route('/')];
    }
}
