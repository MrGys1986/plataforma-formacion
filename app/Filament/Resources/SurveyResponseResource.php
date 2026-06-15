<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\SurveyManagement\SurveyManagementCluster;
use App\Filament\Resources\SurveyResponseResource\Pages\ManageSurveyResponses;
use App\Models\SurveyResponse;

class SurveyResponseResource extends InstitutionalResource
{
    protected static ?int $navigationSort = 3;

    protected static ?string $model = SurveyResponse::class;

    protected static ?string $cluster = SurveyManagementCluster::class;

    protected static ?string $modelLabel = 'Respuesta de encuesta';

    protected static ?string $pluralModelLabel = 'Respuestas de encuestas';

    protected static ?string $recordTitleAttribute = 'id';

    protected static array $formFields = [
        0 => [
            'name' => 'survey_id',
            'label' => 'Encuesta',
            'type' => 'relation',
            'relationship' => 'survey',
            'required' => true,
        ],
        1 => [
            'name' => 'user_id',
            'label' => 'Participante',
            'type' => 'relation',
            'relationship' => 'user',
            'required' => true,
        ],
        2 => [
            'name' => 'activity_id',
            'label' => 'Actividad',
            'type' => 'relation',
            'relationship' => 'activity',
        ],
        3 => [
            'name' => 'diploma_program_id',
            'label' => 'Diplomado',
            'type' => 'relation',
            'relationship' => 'diplomaProgram',
        ],
        4 => [
            'name' => 'answers',
            'label' => 'Respuestas',
            'type' => 'json',
            'required' => true,
        ],
        5 => [
            'name' => 'submitted_at',
            'label' => 'Fecha de envío',
            'type' => 'datetime',
        ],
    ];

    protected static array $tableColumns = [
        0 => [
            'name' => 'survey.name',
            'label' => 'Encuesta',
        ],
        1 => [
            'name' => 'user.name',
            'label' => 'Participante',
        ],
        2 => [
            'name' => 'activity.name',
            'label' => 'Actividad',
        ],
        3 => [
            'name' => 'diplomaProgram.name',
            'label' => 'Diplomado',
        ],
        4 => [
            'name' => 'submitted_at',
            'label' => 'Enviada',
            'type' => 'datetime',
        ],
    ];

    protected static ?string $statusColumn = null;

    protected static bool $readOnly = true;

    public static function getPages(): array
    {
        return [
            'index' => ManageSurveyResponses::route('/'),
        ];
    }
}
