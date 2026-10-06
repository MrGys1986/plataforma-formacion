<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\LearningPathManagement\LearningPathManagementCluster;
use App\Filament\Resources\DiplomaProgramResource\Pages\ManageDiplomaPrograms;
use App\Filament\Resources\DiplomaProgramResource\Pages\EditDiplomaProgram;
use App\Filament\Resources\LearningPathResource\RelationManagers\TrainingProgramsRelationManager;
use App\Models\DiplomaProgram;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;

class DiplomaProgramResource extends InstitutionalResource
{
    protected static bool $shouldRegisterNavigation = true;

    protected static ?int $navigationSort = 2;

    protected static ?string $model = DiplomaProgram::class;

    protected static ?string $cluster = LearningPathManagementCluster::class;

    protected static ?string $modelLabel = 'Diplomado';

    protected static ?string $pluralModelLabel = 'Diplomados';

    protected static ?string $recordTitleAttribute = 'name';

    protected static bool $softDeletes = true;

    protected static bool $readOnly = true;

    protected static array $formFields = [
        ['name' => 'area_id', 'label' => 'Área', 'type' => 'relation', 'relationship' => 'area'],
        ['name' => 'name', 'label' => 'Nombre', 'required' => true],
        ['name' => 'slug', 'label' => 'Identificador', 'required' => true],
        ['name' => 'description', 'label' => 'Descripción', 'type' => 'textarea'],
        ['name' => 'objective', 'label' => 'Objetivo', 'type' => 'textarea'],
        ['name' => 'competencies', 'label' => 'Competencias que desarrolla', 'type' => 'textarea'],
        ['name' => 'completion_criteria', 'label' => 'Criterios de logro', 'type' => 'textarea'],
        ['name' => 'total_hours', 'label' => 'Horas totales', 'type' => 'number'],
        ['name' => 'generates_microcredential', 'label' => 'Otorga microcredencial al completar', 'type' => 'toggle', 'default' => false],
        [
            'name' => 'status',
            'label' => 'Estado',
            'type' => 'select',
            'options' => ['activo' => 'Activo', 'inactivo' => 'Inactivo', 'borrador' => 'Borrador'],
        ],
    ];

    protected static array $tableColumns = [
        ['name' => 'name', 'label' => 'Diplomado', 'searchable' => true],
        ['name' => 'area.name', 'label' => 'Área'],
        ['name' => 'training_programs_count', 'label' => 'Programas'],
        ['name' => 'generates_microcredential', 'label' => 'Microcredencial', 'type' => 'boolean'],
        ['name' => 'total_hours', 'label' => 'Horas'],
        ['name' => 'status', 'label' => 'Estado'],
    ];

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount('trainingPrograms');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageDiplomaPrograms::route('/'),
            'edit' => EditDiplomaProgram::route('/{record}/editar'),
        ];
    }

    public static function getRelations(): array
    {
        return [TrainingProgramsRelationManager::class];
    }

    protected static function getTableRecordActions(): array
    {
        return [Action::make('administrar')->label('Administrar')->icon(Heroicon::OutlinedArrowRight)
            ->button()->url(fn (DiplomaProgram $record): string => static::getUrl('edit', ['record' => $record]))];
    }
}
