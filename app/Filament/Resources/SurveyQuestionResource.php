<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\SurveyManagement\SurveyManagementCluster;
use App\Filament\Resources\SurveyQuestionResource\Pages\ManageSurveyQuestions;
use App\Models\SurveyQuestion;

class SurveyQuestionResource extends InstitutionalResource
{
    protected static ?int $navigationSort = 2;

    protected static ?string $model = SurveyQuestion::class;

    protected static ?string $cluster = SurveyManagementCluster::class;

    protected static ?string $modelLabel = 'Pregunta de encuesta';

    protected static ?string $pluralModelLabel = 'Preguntas de encuesta';

    protected static ?string $recordTitleAttribute = 'question_text';

    protected static array $formFields = [
        0 => [
            'name' => 'survey_id',
            'label' => 'Encuesta',
            'type' => 'relation',
            'relationship' => 'survey',
            'required' => true,
        ],
        1 => [
            'name' => 'question_text',
            'label' => 'Pregunta',
            'type' => 'textarea',
            'required' => true,
        ],
        2 => [
            'name' => 'question_type',
            'label' => 'Tipo de pregunta',
        ],
        3 => [
            'name' => 'options',
            'label' => 'Opciones',
            'type' => 'json',
        ],
        4 => [
            'name' => 'is_required',
            'label' => 'Obligatoria',
            'type' => 'toggle',
        ],
        5 => [
            'name' => 'order_number',
            'label' => 'Orden',
            'type' => 'number',
        ],
    ];

    protected static array $tableColumns = [
        0 => [
            'name' => 'survey.name',
            'label' => 'Encuesta',
        ],
        1 => [
            'name' => 'question_text',
            'label' => 'Pregunta',
            'searchable' => true,
        ],
        2 => [
            'name' => 'question_type',
            'label' => 'Tipo',
        ],
        3 => [
            'name' => 'is_required',
            'label' => 'Obligatoria',
            'type' => 'boolean',
        ],
    ];

    protected static ?string $statusColumn = null;

    protected static bool $readOnly = false;

    public static function getPages(): array
    {
        return [
            'index' => ManageSurveyQuestions::route('/'),
        ];
    }
}
