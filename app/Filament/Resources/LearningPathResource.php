<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\AcademicManagement\AcademicManagementCluster;
use App\Filament\Resources\LearningPathResource\Pages\ManageLearningPaths;
use App\Models\LearningPath;

class LearningPathResource extends InstitutionalResource
{
    protected static ?string $model = LearningPath::class;

    protected static ?string $cluster = AcademicManagementCluster::class;

    protected static ?string $modelLabel = 'Ruta de aprendizaje';

    protected static ?string $pluralModelLabel = 'Rutas de aprendizaje';

    protected static ?string $recordTitleAttribute = 'name';

    protected static array $formFields = [
        0 => [
            'name' => 'area_id',
            'label' => 'Área',
            'type' => 'relation',
            'relationship' => 'area',
        ],
        1 => [
            'name' => 'name',
            'label' => 'Nombre',
            'required' => true,
        ],
        2 => [
            'name' => 'slug',
            'label' => 'Identificador',
            'required' => true,
        ],
        3 => [
            'name' => 'description',
            'label' => 'Descripción',
            'type' => 'textarea',
        ],
        4 => [
            'name' => 'total_hours',
            'label' => 'Total de horas',
            'type' => 'number',
        ],
        5 => [
            'name' => 'status',
            'label' => 'Estado',
        ],
    ];

    protected static array $tableColumns = [
        0 => [
            'name' => 'name',
            'label' => 'Nombre',
            'searchable' => true,
        ],
        1 => [
            'name' => 'area.name',
            'label' => 'Área',
        ],
        2 => [
            'name' => 'total_hours',
            'label' => 'Horas',
        ],
        3 => [
            'name' => 'status',
            'label' => 'Estado',
        ],
    ];

    protected static ?string $statusColumn = 'status';

    protected static bool $readOnly = false;

    public static function getPages(): array
    {
        return [
            'index' => ManageLearningPaths::route('/'),
        ];
    }
}
