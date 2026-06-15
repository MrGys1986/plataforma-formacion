<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\LearningPathManagement\LearningPathManagementCluster;
use App\Filament\Resources\CompetencyResource\Pages\ManageCompetencies;
use App\Models\Competency;
use Illuminate\Database\Eloquent\Builder;

class CompetencyResource extends InstitutionalResource
{
    protected static ?int $navigationSort = 2;

    protected static ?string $model = Competency::class;

    protected static ?string $cluster = LearningPathManagementCluster::class;

    protected static ?string $modelLabel = 'Competencia';

    protected static ?string $pluralModelLabel = 'Competencias';

    protected static ?string $recordTitleAttribute = 'name';

    protected static bool $softDeletes = true;

    protected static array $formFields = [
        ['name' => 'area_id', 'label' => 'Área', 'type' => 'relation', 'relationship' => 'area'],
        ['name' => 'name', 'label' => 'Nombre', 'required' => true],
        ['name' => 'slug', 'label' => 'Identificador', 'required' => true],
        ['name' => 'description', 'label' => 'Descripción', 'type' => 'textarea'],
        ['name' => 'objective', 'label' => 'Objetivo', 'type' => 'textarea'],
        ['name' => 'completion_criteria', 'label' => 'Criterios de logro', 'type' => 'textarea'],
        [
            'name' => 'certificationPrograms',
            'label' => 'Certificaciones requeridas',
            'type' => 'relation',
            'relationship' => 'certificationPrograms',
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
        ['name' => 'name', 'label' => 'Competencia', 'searchable' => true],
        ['name' => 'area.name', 'label' => 'Área'],
        ['name' => 'certification_programs_count', 'label' => 'Certificaciones'],
        ['name' => 'status', 'label' => 'Estado'],
    ];

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount('certificationPrograms');
    }

    public static function getPages(): array
    {
        return ['index' => ManageCompetencies::route('/')];
    }
}
