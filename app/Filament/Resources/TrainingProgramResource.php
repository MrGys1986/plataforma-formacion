<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\TrainingManagement\TrainingManagementCluster;
use App\Filament\Pages\ProgramControlPage;
use App\Filament\Resources\TrainingProgramResource\Pages\ManageTrainingPrograms;
use App\Models\TrainingProgram;
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

class TrainingProgramResource extends InstitutionalResource
{
    protected static bool $shouldRegisterNavigation = true;

    protected static ?int $navigationSort = 1;

    protected static ?string $model = TrainingProgram::class;

    protected static ?string $cluster = TrainingManagementCluster::class;

    protected static ?string $navigationLabel = 'Programas de formación';

    protected static ?string $modelLabel = 'Programa formativo';

    protected static ?string $pluralModelLabel = 'Oferta formativa';

    protected static ?string $recordTitleAttribute = 'name';

    protected static bool $softDeletes = true;

    protected static array $formFields = [
        [
            'name' => 'activity_type_id',
            'label' => 'Tipo',
            'type' => 'relation',
            'relationship' => 'activityType',
            'required' => true,
        ],
        [
            'name' => 'area_id',
            'label' => 'Área responsable',
            'type' => 'relation',
            'relationship' => 'area',
        ],
        [
            'name' => 'name',
            'label' => 'Nombre',
            'required' => true,
        ],
        [
            'name' => 'slug',
            'label' => 'Identificador',
            'required' => true,
        ],
        [
            'name' => 'description',
            'label' => 'Descripción',
            'type' => 'textarea',
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
            'name' => 'general_objective',
            'label' => 'Objetivo general',
            'type' => 'textarea',
        ],
        [
            'name' => 'specific_objectives',
            'label' => 'Objetivos específicos',
            'type' => 'textarea',
        ],
        [
            'name' => 'skills',
            'label' => 'Habilidades desarrolladas',
            'type' => 'textarea',
        ],
        [
            'name' => 'competencies',
            'label' => 'Competencias que desarrolla',
            'type' => 'textarea',
        ],
        [
            'name' => 'default_modality',
            'label' => 'Modalidad predeterminada',
            'type' => 'select',
            'options' => [
                'presencial' => 'Presencial',
                'virtual' => 'Virtual',
                'hibrida' => 'Híbrida',
            ],
            'default' => 'presencial',
            'required' => true,
        ],
        [
            'name' => 'duration_hours',
            'label' => 'Duración en horas',
            'type' => 'number',
            'required' => true,
        ],
        [
            'name' => 'default_cost',
            'label' => 'Costo predeterminado',
            'type' => 'number',
            'default' => 0,
        ],
        [
            'name' => 'is_external',
            'label' => 'Oferta externa',
            'type' => 'toggle',
            'default' => false,
        ],
        [
            'name' => 'requires_payment',
            'label' => 'Requiere pago',
            'type' => 'toggle',
            'default' => false,
        ],
        [
            'name' => 'requires_evaluation',
            'label' => 'Requiere evaluación',
            'type' => 'toggle',
            'default' => false,
        ],
        [
            'name' => 'requires_survey',
            'label' => 'Requiere encuesta',
            'type' => 'toggle',
            'default' => true,
        ],
        [
            'name' => 'generates_certificate',
            'label' => 'Genera constancia',
            'type' => 'toggle',
            'default' => true,
        ],
        [
            'name' => 'generates_microcredential',
            'label' => 'Genera microcredencial',
            'type' => 'toggle',
            'default' => false,
        ],
        [
            'name' => 'status',
            'label' => 'Estado',
            'type' => 'select',
            'options' => [
                'activo' => 'Activo',
                'inactivo' => 'Inactivo',
                'borrador' => 'Borrador',
            ],
            'default' => 'activo',
            'required' => true,
        ],
    ];

    protected static array $tableColumns = [
        [
            'name' => 'name',
            'label' => 'Programa',
            'searchable' => true,
        ],
        [
            'name' => 'activityType.name',
            'label' => 'Tipo',
        ],
        [
            'name' => 'area.name',
            'label' => 'Área',
        ],
        [
            'name' => 'duration_hours',
            'label' => 'Horas',
        ],
        [
            'name' => 'status',
            'label' => 'Estado',
        ],
    ];

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
            'index' => ManageTrainingPrograms::route('/'),
        ];
    }

    protected static function modifyTable(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Programa')
                    ->icon(Heroicon::OutlinedAcademicCap)
                    ->weight('semibold')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('activityType.name')
                    ->label('Tipo')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                TextColumn::make('area.name')
                    ->label('Área')
                    ->icon(Heroicon::OutlinedBuildingOffice2)
                    ->placeholder('Sin asignar')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('duration_hours')
                    ->label('Duración')
                    ->suffix(' h')
                    ->numeric(decimalPlaces: 0)
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => ucfirst($state ?: 'Sin definir'))
                    ->color(fn (?string $state): string => match ($state) {
                        'activo' => 'success',
                        'borrador' => 'warning',
                        'inactivo' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->heading(view('filament.partials.table-heading', [
                'icon' => Heroicon::OutlinedAcademicCap,
                'label' => 'Programas de formación',
            ]))
            ->description('Administra desde un solo lugar los cursos, minicursos y talleres de la oferta institucional.')
            ->headerActions([
                CreateAction::make()
                    ->label('Nuevo programa')
                    ->icon(Heroicon::OutlinedPlus)
                    ->color('primary')
                    ->modalHeading('Registrar programa de formación')
                    ->modalDescription('Define la información académica y operativa del programa de formación.')
                    ->modalIcon(Heroicon::OutlinedAcademicCap)
                    ->modalIconColor('primary')
                    ->modalWidth(Width::FiveExtraLarge)
                    ->modalSubmitActionLabel('Guardar')
                    ->createAnother(false)
                    ->successNotificationTitle('Programa registrado correctamente'),
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
                SelectFilter::make('area_id')
                    ->label('Área responsable')
                    ->placeholder('Todas las áreas')
                    ->relationship('area', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('default_modality')
                    ->label('Modalidad')
                    ->placeholder('Todas las modalidades')
                    ->options([
                        'presencial' => 'Presencial',
                        'virtual' => 'Virtual',
                        'hibrida' => 'Híbrida',
                    ]),
                SelectFilter::make('status')
                    ->label('Estado')
                    ->placeholder('Todos los estados')
                    ->options([
                        'activo' => 'Activo',
                        'inactivo' => 'Inactivo',
                        'borrador' => 'Borrador',
                    ]),
            ], FiltersLayout::AboveContent)
            ->filtersFormColumns([
                'default' => 1,
                'md' => 2,
                'xl' => 4,
            ])
            ->deferFilters(false)
            ->searchPlaceholder('Buscar programa de formación')
            ->defaultPaginationPageOption(25)
            ->persistSearchInSession(false)
            ->persistFiltersInSession(false)
            ->emptyStateIcon(Heroicon::OutlinedAcademicCap)
            ->emptyStateHeading('No se encontraron programas')
            ->emptyStateDescription('Ajusta los filtros o registra el primer programa de formación.')
            ->extraAttributes(['class' => 'pf-management-table pf-training-program-table']);
    }

    /**
     * @return array<Action|ActionGroup>
     */
    protected static function getTableRecordActions(): array
    {
        return [
            Action::make('administrar')
                ->label('Administrar')
                ->icon(Heroicon::OutlinedArrowRight)
                ->button()
                ->color('primary')
                ->url(fn (TrainingProgram $record): string => ProgramControlPage::getUrl(['record' => $record->getKey()])),
        ];
    }
}
