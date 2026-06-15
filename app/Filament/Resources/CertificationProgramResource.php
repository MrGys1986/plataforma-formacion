<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\LearningPathManagement\LearningPathManagementCluster;
use App\Filament\Resources\CertificationProgramResource\Pages\ManageCertificationPrograms;
use App\Models\CertificationProgram;
use Illuminate\Database\Eloquent\Builder;

class CertificationProgramResource extends InstitutionalResource
{
    protected static ?int $navigationSort = 3;

    protected static ?string $model = CertificationProgram::class;

    protected static ?string $cluster = LearningPathManagementCluster::class;

    protected static ?string $modelLabel = 'Certificación';

    protected static ?string $pluralModelLabel = 'Certificaciones';

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
            'name' => 'diplomaPrograms',
            'label' => 'Diplomados requeridos',
            'type' => 'relation',
            'relationship' => 'diplomaPrograms',
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
        ['name' => 'name', 'label' => 'Certificación', 'searchable' => true],
        ['name' => 'area.name', 'label' => 'Área'],
        ['name' => 'diploma_programs_count', 'label' => 'Diplomados'],
        ['name' => 'status', 'label' => 'Estado'],
    ];

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount('diplomaPrograms');
    }

    public static function getPages(): array
    {
        return ['index' => ManageCertificationPrograms::route('/')];
    }
}
