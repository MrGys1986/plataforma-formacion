<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\TrainingManagement\TrainingManagementCluster;
use App\Filament\Resources\SurveyQuestionResource\Pages\ManageSurveyQuestions;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use Filament\Actions\CreateAction;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SurveyQuestionResource extends InstitutionalResource
{
    protected static ?int $navigationSort = 2;

    protected static ?string $model = SurveyQuestion::class;

    protected static ?string $cluster = TrainingManagementCluster::class;

    protected static ?string $navigationLabel = 'Preguntas de satisfacción';

    protected static ?string $modelLabel = 'Pregunta de encuesta';

    protected static ?string $pluralModelLabel = 'Preguntas de satisfacción';

    protected static ?string $recordTitleAttribute = 'question_text';

    protected static array $formFields = [];

    protected static array $tableColumns = [
        0 => [
            'name' => 'question_text',
            'label' => 'Pregunta',
            'searchable' => true,
        ],
        1 => [
            'name' => 'question_type',
            'label' => 'Tipo',
        ],
        2 => [
            'name' => 'is_required',
            'label' => 'Obligatoria',
            'type' => 'boolean',
        ],
        3 => [
            'name' => 'order_number',
            'label' => 'Orden',
        ],
    ];

    protected static ?string $statusColumn = null;

    protected static bool $readOnly = false;

    public static function form(Schema $schema): Schema
    {
        return parent::form($schema)
            ->columns([
                'default' => 1,
                'md' => 2,
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('survey', fn (Builder $query): Builder => $query->where('is_general', true));
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSurveyQuestions::route('/'),
        ];
    }

    protected static function getFormFields(): array
    {
        return [
            [
                'name' => 'survey_id',
                'label' => 'Encuesta institucional',
                'type' => 'hidden',
                'default' => fn (): int => static::getGeneralSurveyId(),
                'required' => true,
            ],
            [
                'name' => 'question_text',
                'label' => 'Pregunta',
                'type' => 'textarea',
                'required' => true,
            ],
            [
                'name' => 'question_type',
                'label' => 'Tipo de respuesta',
                'type' => 'select',
                'options' => [
                    'escala' => 'Escala del 1 al 5',
                    'texto' => 'Respuesta abierta',
                    'opcion_multiple' => 'Opción múltiple',
                ],
                'default' => 'escala',
                'required' => true,
            ],
            [
                'name' => 'options',
                'label' => 'Opciones de respuesta',
                'type' => 'json',
            ],
            [
                'name' => 'is_required',
                'label' => 'Respuesta obligatoria',
                'type' => 'toggle',
                'default' => true,
            ],
            [
                'name' => 'order_number',
                'label' => 'Orden de aparición',
                'type' => 'number',
                'default' => 1,
                'required' => true,
            ],
        ];
    }

    protected static function modifyTable(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('Orden')
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                TextColumn::make('question_text')
                    ->label('Pregunta')
                    ->icon(Heroicon::OutlinedQuestionMarkCircle)
                    ->weight('semibold')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('question_type')
                    ->label('Tipo de respuesta')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'escala' => 'Escala del 1 al 5',
                        'texto' => 'Respuesta abierta',
                        'opcion_multiple' => 'Opción múltiple',
                        default => ucfirst($state ?: 'Sin definir'),
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'escala' => 'primary',
                        'texto' => 'info',
                        'opcion_multiple' => 'warning',
                        default => 'gray',
                    }),
                IconColumn::make('is_required')
                    ->label('Obligatoria')
                    ->boolean(),
            ])
            ->heading(view('filament.partials.table-heading', [
                'icon' => Heroicon::OutlinedQuestionMarkCircle,
                'label' => 'Preguntas de satisfacción',
            ]))
            ->description('Estas preguntas forman una encuesta institucional única y se utilizan en todos los cursos.')
            ->headerActions([
                CreateAction::make()
                    ->label('Nueva pregunta')
                    ->icon(Heroicon::OutlinedPlus)
                    ->color('primary')
                    ->modalHeading('Agregar pregunta de satisfacción')
                    ->modalDescription('La pregunta estará disponible para la encuesta de todos los programas de formación.')
                    ->modalIcon(Heroicon::OutlinedQuestionMarkCircle)
                    ->modalIconColor('primary')
                    ->modalWidth(Width::TwoExtraLarge)
                    ->modalSubmitActionLabel('Guardar')
                    ->createAnother(false)
                    ->successNotificationTitle('Pregunta registrada correctamente'),
            ])
            ->filters([
                SelectFilter::make('question_type')
                    ->label('Tipo de respuesta')
                    ->placeholder('Todos los tipos')
                    ->options([
                        'escala' => 'Escala del 1 al 5',
                        'texto' => 'Respuesta abierta',
                        'opcion_multiple' => 'Opción múltiple',
                    ]),
                SelectFilter::make('is_required')
                    ->label('Aplicación')
                    ->placeholder('Todas las preguntas')
                    ->options([
                        '1' => 'Obligatorias',
                        '0' => 'Opcionales',
                    ]),
            ], FiltersLayout::AboveContent)
            ->filtersFormColumns([
                'default' => 1,
                'md' => 2,
            ])
            ->deferFilters(false)
            ->defaultSort('order_number')
            ->searchPlaceholder('Buscar pregunta')
            ->defaultPaginationPageOption(25)
            ->persistSearchInSession(false)
            ->persistFiltersInSession(false)
            ->emptyStateIcon(Heroicon::OutlinedQuestionMarkCircle)
            ->emptyStateHeading('No hay preguntas configuradas')
            ->emptyStateDescription('Agrega la primera pregunta de la encuesta institucional de satisfacción.')
            ->extraAttributes(['class' => 'pf-management-table pf-survey-question-table']);
    }

    protected static function getGeneralSurveyId(): int
    {
        return Survey::query()->firstOrCreate(
            ['is_general' => true],
            [
                'name' => 'Encuesta general de satisfacción',
                'description' => 'Encuesta institucional para todas las actividades de formación.',
                'status' => 'activa',
                'created_by' => auth()->id(),
            ],
        )->getKey();
    }
}
