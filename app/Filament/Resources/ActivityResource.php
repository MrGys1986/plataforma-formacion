<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\TrainingManagement\TrainingManagementCluster;
use App\Filament\Pages\EditionControlPage;
use App\Filament\Resources\ActivityResource\Pages\ManageActivities;
use App\Models\Activity;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ActivityResource extends InstitutionalResource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 2;

    protected static ?string $model = Activity::class;

    protected static ?string $cluster = TrainingManagementCluster::class;

    protected static ?string $navigationLabel = 'Cursos y actividades';

    protected static ?string $modelLabel = 'Actividad formativa';

    protected static ?string $pluralModelLabel = 'Cursos y actividades';

    protected static ?string $recordTitleAttribute = 'name';

    protected static bool $softDeletes = true;

    protected static array $tableColumns = [
        [
            'name' => 'name',
            'label' => 'Nombre',
            'searchable' => true,
        ],
        [
            'name' => 'activityType.name',
            'label' => 'Tipo',
        ],
        [
            'name' => 'trainingProgram.name',
            'label' => 'Programa',
        ],
        [
            'name' => 'end_date',
            'label' => 'Vigente hasta',
            'type' => 'date',
        ],
        [
            'name' => 'start_date',
            'label' => 'Inicio',
            'type' => 'date',
        ],
        [
            'name' => 'status',
            'label' => 'Estado',
        ],
    ];

    protected static ?string $statusColumn = 'status';

    protected static bool $readOnly = false;

    public static function form(Schema $schema): Schema
    {
        return parent::form($schema)
            ->columns([
                'default' => 1,
                'md' => 2,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageActivities::route('/'),
        ];
    }

    protected static function getFormFields(): array
    {
        return [
            [
                'name' => 'training_program_id',
                'label' => 'Programa de formación',
                'type' => 'relation',
                'relationship' => 'trainingProgram',
                'required' => true,
                'default' => fn (): ?int => request()->filled('training_program')
                    ? request()->integer('training_program')
                    : null,
            ],
            [
                'name' => 'activity_type_id',
                'label' => 'Tipo de actividad',
                'type' => 'relation',
                'relationship' => 'activityType',
                'required' => true,
            ],
            [
                'name' => 'name',
                'label' => 'Nombre',
                'required' => true,
            ],
            [
                'name' => 'slug',
                'label' => 'Identificador URL',
                'required' => true,
            ],
            [
                'name' => 'description',
                'label' => 'Descripción',
                'type' => 'textarea',
            ],
            [
                'name' => 'competencies',
                'label' => 'Competencias de esta edición',
                'type' => 'textarea',
            ],
            [
                'name' => 'duration_hours',
                'label' => 'Duración en horas',
                'type' => 'number',
            ],
            [
                'name' => 'area_id',
                'label' => 'Área',
                'type' => 'relation',
                'relationship' => 'area',
            ],
            [
                'name' => 'instructor_id',
                'label' => 'Profesor responsable',
                'type' => 'relation',
                'relationship' => 'instructor',
                'role' => ['Profesor', 'Personal'],
            ],
            [
                'name' => 'modality',
                'label' => 'Modalidad',
                'type' => 'select',
                'options' => [
                    'presencial' => 'Presencial',
                    'virtual' => 'Virtual',
                    'hibrida' => 'Híbrida',
                ],
            ],
            [
                'name' => 'cover_file_id',
                'label' => 'Imagen de portada',
                'type' => 'file',
                'directory' => 'course-covers',
                'public_image' => true,
                'accepted_types' => ['image/jpeg', 'image/png', 'image/webp'],
                'max_size' => 5120,
            ],
            [
                'name' => 'start_date',
                'label' => 'Fecha de inicio',
                'type' => 'date',
            ],
            [
                'name' => 'end_date',
                'label' => 'Fecha de cierre',
                'type' => 'date',
            ],
            [
                'name' => 'enrollment_start_date',
                'label' => 'Inicio de inscripciones',
                'type' => 'date',
            ],
            [
                'name' => 'enrollment_end_date',
                'label' => 'Cierre de inscripciones',
                'type' => 'date',
            ],
            [
                'name' => 'schedule',
                'label' => 'Horario',
            ],
            [
                'name' => 'classroom_url',
                'label' => 'Enlace de Google Classroom',
            ],
            [
                'name' => 'max_capacity',
                'label' => 'Cupo máximo',
                'type' => 'number',
            ],
            [
                'name' => 'cost',
                'label' => 'Costo',
                'type' => 'number',
            ],
            [
                'name' => 'status',
                'label' => 'Estado',
                'type' => 'select',
                'options' => [
                    'borrador' => 'Borrador',
                    'publicado' => 'Publicado',
                    'en_inscripcion' => 'En inscripción',
                    'cupo_lleno' => 'Cupo lleno',
                    'en_curso' => 'En curso',
                    'finalizado' => 'Finalizado',
                    'cancelado' => 'Cancelado',
                    'archivado' => 'Archivado',
                ],
            ],
        ];
    }

    protected static function applyContextToQuery(Builder $query): Builder
    {
        if (! request()->filled('training_program')) {
            return $query;
        }

        return $query->where('training_program_id', request()->integer('training_program'));
    }

    protected static function modifyTable(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Curso o edición')
                    ->icon(Heroicon::OutlinedRectangleStack)
                    ->weight('semibold')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('activityType.name')
                    ->label('Tipo')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                TextColumn::make('trainingProgram.name')
                    ->label('Programa')
                    ->placeholder('Sin programa')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('start_date')
                    ->label('Inicio')
                    ->date('d/m/Y')
                    ->placeholder('Pendiente')
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('Cierre')
                    ->date('d/m/Y')
                    ->placeholder('Pendiente')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => str($state ?: 'Sin definir')->replace('_', ' ')->title()->toString())
                    ->color(fn (?string $state): string => match ($state) {
                        'publicado', 'en_inscripcion', 'cupo_lleno', 'en_curso' => 'success',
                        'borrador' => 'warning',
                        'finalizado' => 'info',
                        'cancelado', 'archivado' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->heading(view('filament.partials.table-heading', [
                'icon' => Heroicon::OutlinedRectangleStack,
                'label' => 'Cursos y ediciones',
            ]))
            ->description('Filtra por tipo o etapa operativa y entra a cada curso para administrar toda su información.')
            ->headerActions([
                CreateAction::make()
                    ->label('Nueva edición')
                    ->icon(Heroicon::OutlinedPlus)
                    ->color('primary')
                    ->modalHeading('Registrar curso o edición')
                    ->modalDescription('Selecciona el programa y completa los datos operativos de esta edición.')
                    ->modalIcon(Heroicon::OutlinedRectangleStack)
                    ->modalIconColor('primary')
                    ->modalWidth(Width::FiveExtraLarge)
                    ->modalSubmitActionLabel('Guardar')
                    ->createAnother(false)
                    ->successNotificationTitle('Edición registrada correctamente'),
            ])
            ->filters([
                SelectFilter::make('activity_type_id')
                    ->label('Tipo de formación')
                    ->placeholder('Cursos, minicursos y talleres')
                    ->relationship(
                        'activityType',
                        'name',
                        fn (Builder $query): Builder => $query
                            ->whereIn('name', ['Curso', 'Minicurso', 'Taller'])
                            ->orderBy('name'),
                    )
                    ->preload(),
                SelectFilter::make('operation_state')
                    ->label('Etapa operativa')
                    ->placeholder('En operación y cerrados')
                    ->options([
                        'operacion' => 'En operación',
                        'cerrados' => 'Cerrados',
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query->when(
                        filled($data['value'] ?? null),
                        fn (Builder $activities): Builder => $data['value'] === 'operacion'
                            ? $activities->whereIn('status', ['publicado', 'en_inscripcion', 'cupo_lleno', 'en_curso'])
                            : $activities->whereIn('status', ['finalizado', 'cancelado', 'archivado']),
                    )),
                SelectFilter::make('status')
                    ->label('Estado específico')
                    ->placeholder('Todos los estados')
                    ->options([
                        'borrador' => 'Borrador',
                        'publicado' => 'Publicado',
                        'en_inscripcion' => 'En inscripción',
                        'cupo_lleno' => 'Cupo lleno',
                        'en_curso' => 'En curso',
                        'finalizado' => 'Finalizado',
                        'cancelado' => 'Cancelado',
                        'archivado' => 'Archivado',
                    ]),
                SelectFilter::make('training_program_id')
                    ->label('Programa')
                    ->placeholder('Todos los programas')
                    ->relationship('trainingProgram', 'name')
                    ->searchable()
                    ->preload(),
            ], FiltersLayout::AboveContent)
            ->filtersFormColumns([
                'default' => 1,
                'md' => 2,
                'xl' => 4,
            ])
            ->deferFilters(false)
            ->searchPlaceholder('Buscar curso, edición o programa')
            ->defaultPaginationPageOption(25)
            ->persistSearchInSession(false)
            ->persistFiltersInSession(false)
            ->emptyStateIcon(Heroicon::OutlinedRectangleStack)
            ->emptyStateHeading('No se encontraron cursos o ediciones')
            ->emptyStateDescription('Ajusta los filtros o registra una nueva edición para comenzar su operación.')
            ->extraAttributes(['class' => 'pf-management-table pf-activity-table']);
    }

    /**
     * @return array<Action|ActionGroup>
     */
    protected static function getTableRecordActions(): array
    {
        return [
            Action::make('control')
                ->label('Administrar')
                ->icon(Heroicon::OutlinedArrowRight)
                ->button()
                ->color('primary')
                ->url(fn (Activity $record): string => EditionControlPage::getUrl(['record' => $record->getKey()])),
        ];
    }
}
