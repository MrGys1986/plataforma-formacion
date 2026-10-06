<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\LearningPathManagement\LearningPathManagementCluster;
use App\Filament\Pages\LearningPathControlPage;
use App\Filament\Resources\LearningPathResource\Pages\EditLearningPath;
use App\Filament\Resources\LearningPathResource\Pages\ManageLearningPaths;
use App\Filament\Resources\LearningPathResource\RelationManagers\CertificationProgramsRelationManager;
use App\Filament\Resources\LearningPathResource\RelationManagers\DiplomaProgramsRelationManager;
use App\Filament\Resources\LearningPathResource\RelationManagers\LegacyActivitiesRelationManager;
use App\Filament\Resources\LearningPathResource\RelationManagers\TrainingProgramsRelationManager;
use App\Filament\Resources\LearningPathResource\RelationManagers\UserLearningPathsRelationManager;
use App\Models\LearningPath;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class LearningPathResource extends InstitutionalResource
{
    protected static ?int $navigationSort = 1;

    protected static ?string $model = LearningPath::class;

    protected static ?string $cluster = LearningPathManagementCluster::class;

    protected static ?string $modelLabel = 'Ruta de aprendizaje';

    protected static ?string $pluralModelLabel = 'Rutas de aprendizaje';

    protected static ?string $recordTitleAttribute = 'name';

    protected static bool $softDeletes = true;

    protected static array $formFields = [];

    protected static array $tableColumns = [];

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información de la ruta')
                    ->description('Define el tipo de reconocimiento, el alcance y la forma de avance.')
                    ->icon(Heroicon::OutlinedMap)
                    ->schema([
                        Select::make('area_id')
                            ->label('Área responsable')
                            ->relationship('area', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                        TextInput::make('slug')
                            ->label('Identificador')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Select::make('status')
                            ->label('Estado')
                            ->options([
                                'borrador' => 'Borrador',
                                'publicada' => 'Publicada',
                                'inactiva' => 'Inactiva',
                                'archivada' => 'Archivada',
                            ])
                            ->default('borrador')
                            ->native(false)
                            ->required(),
                        TextInput::make('total_hours')
                            ->label('Total de horas')
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                        Textarea::make('description')
                            ->label('Descripción')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('objective')
                            ->label('Objetivo')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('competencies')
                            ->label('Competencias que desarrolla')
                            ->helperText('Descríbelas aquí; no es necesario crearlas ni vincularlas por separado.')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('target_audience')
                            ->label('Dirigido a')
                            ->rows(3)
                            ->columnSpanFull(),
                        Toggle::make('is_sequential')
                            ->label('Avance secuencial')
                            ->helperText('Los programas deberán completarse en el orden establecido.'),
                        Toggle::make('generates_microcredential')
                            ->label('Otorga microcredencial al completar la ruta')
                            ->default(false),
                    ])
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount(['trainingPrograms', 'diplomaPrograms', 'certificationPrograms', 'userLearningPaths', 'items']);
    }

    public static function getRelations(): array
    {
        return [
            DiplomaProgramsRelationManager::class,
            CertificationProgramsRelationManager::class,
            TrainingProgramsRelationManager::class,
            UserLearningPathsRelationManager::class,
            LegacyActivitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageLearningPaths::route('/'),
            'edit' => EditLearningPath::route('/{record}/editar'),
        ];
    }

    protected static function modifyTable(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Ruta de aprendizaje')
                    ->icon(Heroicon::OutlinedMap)
                    ->weight('semibold')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('diploma_programs_count')->label('Diplomados')->badge(),
                TextColumn::make('certification_programs_count')->label('Certificaciones')->badge(),
                TextColumn::make('training_programs_count')
                    ->label('Programas')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('items_count')->label('Act. anteriores')->badge()->color('gray'),
                TextColumn::make('total_hours')
                    ->label('Duración')
                    ->suffix(' h')
                    ->numeric(decimalPlaces: 0)
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => ucfirst($state ?: 'Sin definir'))
                    ->color(fn (?string $state): string => match ($state) {
                        'publicada' => 'success',
                        'borrador' => 'warning',
                        'inactiva', 'archivada' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->heading(view('filament.partials.table-heading', [
                'icon' => Heroicon::OutlinedMap,
                'label' => 'Rutas de aprendizaje',
            ]))
            ->description('Agrupa diplomados, certificaciones y programas reutilizables en itinerarios formativos.')
            ->headerActions([
                CreateAction::make()
                    ->label('Nueva ruta')
                    ->icon(Heroicon::OutlinedPlus)
                    ->modalHeading('Crear ruta de aprendizaje')
                    ->modalDescription('Registra la ruta y después vincula diplomados, certificaciones y programas.')
                    ->modalIcon(Heroicon::OutlinedMap)
                    ->modalWidth(Width::FiveExtraLarge)
                    ->modalSubmitActionLabel('Guardar')
                    ->createAnother(false)
                    ->successNotificationTitle('Ruta creada correctamente'),
            ])
            ->filters([
                SelectFilter::make('generates_microcredential')
                    ->label('Microcredencial')
                    ->options([1 => 'Sí', 0 => 'No']),
                SelectFilter::make('area_id')
                    ->label('Área responsable')
                    ->placeholder('Todas las áreas')
                    ->relationship('area', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status')
                    ->label('Estado')
                    ->placeholder('Todos los estados')
                    ->options([
                        'borrador' => 'Borrador',
                        'publicada' => 'Publicada',
                        'inactiva' => 'Inactiva',
                        'archivada' => 'Archivada',
                    ]),
            ], FiltersLayout::AboveContent)
            ->filtersFormColumns([
                'default' => 1,
                'md' => 3,
            ])
            ->deferFilters(false)
            ->searchPlaceholder('Buscar ruta de aprendizaje')
            ->defaultPaginationPageOption(25)
            ->persistSearchInSession(false)
            ->persistFiltersInSession(false)
            ->emptyStateIcon(Heroicon::OutlinedMap)
            ->emptyStateHeading('No se encontraron rutas')
            ->emptyStateDescription('Ajusta los filtros o registra la primera ruta de aprendizaje.')
            ->extraAttributes(['class' => 'pf-management-table pf-learning-path-table']);
    }

    protected static function getTableRecordActions(): array
    {
        return [
            Action::make('administrar')
                ->label('Administrar')
                ->icon(Heroicon::OutlinedArrowRight)
                ->button()
                ->color('primary')
                ->url(fn (LearningPath $record): string => LearningPathControlPage::getUrl([
                    'record' => $record->getKey(),
                ])),
        ];
    }
}
