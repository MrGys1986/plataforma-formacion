<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\LearningPathManagement\LearningPathManagementCluster;
use App\Filament\Resources\LearningPathResource\Pages\ManageLearningPaths;
use App\Models\LearningPath;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
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
            'name' => 'competencyDefinitions',
            'label' => 'Competencias de la ruta',
            'type' => 'relation',
            'relationship' => 'competencyDefinitions',
            'multiple' => true,
        ],
        6 => [
            'name' => 'status',
            'label' => 'Estado',
            'type' => 'select',
            'options' => [
                'borrador' => 'Borrador',
                'publicada' => 'Publicada',
                'inactiva' => 'Inactiva',
                'archivada' => 'Archivada',
            ],
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
            'name' => 'competency_definitions_count',
            'label' => 'Competencias',
        ],
        4 => [
            'name' => 'status',
            'label' => 'Estado',
        ],
    ];

    protected static ?string $statusColumn = 'status';

    protected static bool $readOnly = false;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Información general')
                ->schema([
                    Select::make('area_id')
                        ->label('Área')
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
                        ->required(),
                    Textarea::make('description')
                        ->label('Descripción')
                        ->columnSpanFull(),
                    Textarea::make('objective')
                        ->label('Objetivo')
                        ->columnSpanFull(),
                    Textarea::make('target_audience')
                        ->label('Dirigido a')
                        ->columnSpanFull(),
                    TextInput::make('total_hours')
                        ->label('Total de horas')
                        ->numeric()
                        ->default(0),
                    Toggle::make('is_sequential')
                        ->label('Exigir el orden de las actividades'),
                    Toggle::make('generates_diploma')
                        ->label('Genera diploma')
                        ->default(true),
                    Toggle::make('generates_microcredential')
                        ->label('Genera microcredencial'),
                ])
                ->columns(2),
            Section::make('Actividades de la ruta')
                ->description('Arrastra las actividades para definir el orden de avance.')
                ->schema([
                    Repeater::make('items')
                        ->label('Actividades')
                        ->relationship()
                        ->schema([
                            Select::make('activity_id')
                                ->label('Actividad')
                                ->relationship('activity', 'name')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->distinct(),
                            Toggle::make('is_required')
                                ->label('Obligatoria')
                                ->default(true),
                            TextInput::make('minimum_score')
                                ->label('Calificación mínima')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(100),
                        ])
                        ->orderColumn('order_number')
                        ->reorderable()
                        ->columns(3)
                        ->columnSpanFull(),
                ]),
            Section::make('Participantes asignados')
                ->description('Al asignar una persona se crean automáticamente sus inscripciones a las actividades.')
                ->schema([
                    Repeater::make('userLearningPaths')
                        ->label('Participantes')
                        ->relationship()
                        ->schema([
                            Select::make('user_id')
                                ->label('Participante')
                                ->relationship('user', 'name')
                                ->searchable(['name', 'email'])
                                ->preload()
                                ->required()
                                ->distinct(),
                            Select::make('status')
                                ->label('Estado')
                                ->options([
                                    'no_iniciada' => 'No iniciada',
                                    'en_progreso' => 'En progreso',
                                    'completada' => 'Completada',
                                ])
                                ->default('no_iniciada')
                                ->disabled()
                                ->dehydrated(),
                            TextInput::make('progress_percentage')
                                ->label('Avance (%)')
                                ->numeric()
                                ->default(0)
                                ->disabled()
                                ->dehydrated(),
                        ])
                        ->columns(3)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount('competencyDefinitions');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageLearningPaths::route('/'),
        ];
    }
}
