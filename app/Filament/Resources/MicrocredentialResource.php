<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\LearningPathManagement\LearningPathManagementCluster;
use App\Filament\Resources\MicrocredentialResource\Pages\ManageMicrocredentials;
use App\Models\Microcredential;
use Filament\Actions\CreateAction;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MicrocredentialResource extends InstitutionalResource
{
    protected static ?int $navigationSort = 4;

    protected static ?string $model = Microcredential::class;

    protected static ?string $cluster = LearningPathManagementCluster::class;

    protected static ?string $modelLabel = 'Microcredencial';

    protected static ?string $pluralModelLabel = 'Microcredenciales';

    protected static ?string $recordTitleAttribute = 'name';

    protected static array $formFields = [
        [
            'name' => 'user_id',
            'label' => 'Titular',
            'type' => 'relation',
            'relationship' => 'user',
            'required' => true,
        ],
        [
            'name' => 'activity_id',
            'label' => 'Actividad',
            'type' => 'relation',
            'relationship' => 'activity',
        ],
        [
            'name' => 'certificate_id',
            'label' => 'Constancia',
            'type' => 'relation',
            'relationship' => 'certificate',
            'title' => 'folio',
        ],
        ['name' => 'learning_path_id', 'label' => 'Ruta', 'type' => 'relation', 'relationship' => 'learningPath'],
        ['name' => 'diploma_program_id', 'label' => 'Diplomado', 'type' => 'relation', 'relationship' => 'diplomaProgram'],
        ['name' => 'certification_program_id', 'label' => 'Certificación', 'type' => 'relation', 'relationship' => 'certificationProgram'],
        [
            'name' => 'name',
            'label' => 'Nombre',
            'required' => true,
        ],
        [
            'name' => 'description',
            'label' => 'Descripción',
            'type' => 'textarea',
        ],
        [
            'name' => 'competencies',
            'label' => 'Competencias acreditadas',
            'type' => 'textarea',
        ],
        [
            'name' => 'status',
            'label' => 'Estado',
            'type' => 'select',
            'options' => ['pendiente' => 'Pendiente', 'validada' => 'Validada', 'revocada' => 'Revocada'],
            'default' => 'validada',
            'required' => true,
        ],
    ];

    protected static array $tableColumns = [
        [
            'name' => 'name',
            'label' => 'Nombre',
            'searchable' => true,
        ],
        [
            'name' => 'user.name',
            'label' => 'Titular',
        ],
        [
            'name' => 'activity.name',
            'label' => 'Actividad',
        ],
        [
            'name' => 'learningPath.name',
            'label' => 'Ruta',
        ],
        ['name' => 'diplomaProgram.name', 'label' => 'Diplomado'],
        ['name' => 'certificationProgram.name', 'label' => 'Certificación'],
        [
            'name' => 'issued_at',
            'label' => 'Emisión',
            'type' => 'datetime',
        ],
        [
            'name' => 'status',
            'label' => 'Estado',
        ],
    ];

    protected static ?string $statusColumn = 'status';

    protected static bool $readOnly = false;

    public static function getPages(): array
    {
        return [
            'index' => ManageMicrocredentials::route('/'),
        ];
    }

    protected static function modifyTable(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Microcredencial')->icon(Heroicon::OutlinedSparkles)
                    ->weight('semibold')->searchable()->sortable()->wrap(),
                TextColumn::make('user.name')->label('Titular')->searchable(),
                TextColumn::make('learningPath.name')->label('Ruta')->placeholder('—'),
                TextColumn::make('diplomaProgram.name')->label('Diplomado')->placeholder('—'),
                TextColumn::make('certificationProgram.name')->label('Certificación')->placeholder('—'),
                TextColumn::make('activity.name')->label('Curso / taller')->placeholder('—'),
                TextColumn::make('issued_at')->label('Emisión')->date('d/m/Y')->sortable(),
                TextColumn::make('status')->label('Estado')->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'validada' => 'success', 'pendiente' => 'warning', 'revocada' => 'danger', default => 'gray',
                    }),
            ])
            ->heading(view('filament.partials.table-heading', [
                'icon' => Heroicon::OutlinedSparkles,
                'label' => 'Microcredenciales',
            ]))
            ->description('Consulta y gestiona los reconocimientos de rutas, diplomados, certificaciones y programas.')
            ->headerActions([
                CreateAction::make()->label('Nueva microcredencial')->icon(Heroicon::OutlinedPlus)
                    ->modalHeading('Emitir microcredencial')->modalWidth(Width::FiveExtraLarge)
                    ->createAnother(false),
            ])
            ->filters([
                SelectFilter::make('status')->label('Estado')->options([
                    'pendiente' => 'Pendiente', 'validada' => 'Validada', 'revocada' => 'Revocada',
                ]),
            ], FiltersLayout::AboveContent)
            ->searchPlaceholder('Buscar microcredencial')
            ->emptyStateHeading('No hay microcredenciales')
            ->emptyStateDescription('Se emitirán al completar elementos que tengan esta opción activada.');
    }
}
