<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\SurveyManagement\SurveyManagementCluster;
use App\Filament\Resources\SurveyResource\Pages\ManageSurveys;
use App\Models\Survey;

class SurveyResource extends InstitutionalResource
{
    protected static ?int $navigationSort = 1;

    protected static ?string $model = Survey::class;

    protected static ?string $cluster = SurveyManagementCluster::class;

    protected static ?string $modelLabel = 'Encuesta';

    protected static ?string $pluralModelLabel = 'Encuestas';

    protected static ?string $recordTitleAttribute = 'name';

    protected static array $formFields = [
        0 => [
            'name' => 'name',
            'label' => 'Nombre',
            'required' => true,
        ],
        1 => [
            'name' => 'description',
            'label' => 'Descripción',
            'type' => 'textarea',
        ],
        2 => [
            'name' => 'is_general',
            'label' => 'Encuesta general',
            'type' => 'toggle',
        ],
        3 => [
            'name' => 'trainingPrograms',
            'label' => 'Cursos, minicursos y talleres',
            'type' => 'relation',
            'relationship' => 'trainingPrograms',
            'multiple' => true,
        ],
        4 => [
            'name' => 'diplomaPrograms',
            'label' => 'Diplomados',
            'type' => 'relation',
            'relationship' => 'diplomaPrograms',
            'multiple' => true,
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
            'name' => 'is_general',
            'label' => 'General',
            'type' => 'boolean',
        ],
        2 => [
            'name' => 'status',
            'label' => 'Estado',
        ],
    ];

    protected static ?string $statusColumn = 'status';

    protected static bool $readOnly = false;

    public static function getPages(): array
    {
        return [
            'index' => ManageSurveys::route('/'),
        ];
    }
}
