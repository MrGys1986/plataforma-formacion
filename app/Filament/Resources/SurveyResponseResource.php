<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\TrainingManagement\TrainingManagementCluster;
use App\Filament\Resources\SurveyResponseResource\Pages\ManageSurveyResponses;
use App\Models\SurveyResponse;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SurveyResponseResource extends InstitutionalResource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 4;

    protected static ?string $model = SurveyResponse::class;

    protected static ?string $cluster = TrainingManagementCluster::class;

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
            'searchable' => true,
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

    protected static function applyContextToQuery(Builder $query): Builder
    {
        if (request()->filled('training_program')) {
            return $query->whereHas(
                'activity',
                fn (Builder $activity): Builder => $activity->where(
                    'training_program_id',
                    request()->integer('training_program'),
                ),
            );
        }

        if (! request()->filled('activity')) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('activity_id', request()->integer('activity'));
    }

    protected static function modifyTable(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Participante')
                    ->icon(Heroicon::OutlinedUser)
                    ->weight('semibold')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('survey.name')
                    ->label('Encuesta')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('submitted_at')
                    ->label('Enviada')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('Pendiente')
                    ->sortable(),
            ])
            ->heading(view('filament.partials.table-heading', [
                'icon' => Heroicon::OutlinedChatBubbleLeftRight,
                'label' => 'Encuestas de satisfacción',
            ]))
            ->description('Consulta las respuestas enviadas por las personas participantes de este curso.')
            ->filters([
                SelectFilter::make('user_id')
                    ->label('Participante')
                    ->placeholder('Todas las personas')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('survey_id')
                    ->label('Encuesta')
                    ->placeholder('Todas las encuestas')
                    ->relationship(
                        'survey',
                        'name',
                        fn (Builder $query): Builder => $query->where('is_general', true),
                    )
                    ->preload(),
            ], FiltersLayout::AboveContent)
            ->filtersFormColumns([
                'default' => 1,
                'md' => 2,
            ])
            ->deferFilters(false)
            ->searchPlaceholder('Buscar participante')
            ->defaultSort('submitted_at', 'desc')
            ->defaultPaginationPageOption(25)
            ->persistSearchInSession(false)
            ->persistFiltersInSession(false)
            ->emptyStateIcon(Heroicon::OutlinedChatBubbleLeftRight)
            ->emptyStateHeading('Aún no hay respuestas')
            ->emptyStateDescription('Las respuestas aparecerán aquí cuando las personas completen la encuesta del curso.')
            ->extraAttributes(['class' => 'pf-management-table pf-survey-response-table']);
    }
}
