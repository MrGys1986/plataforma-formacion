<?php
namespace App\Filament\Resources;
use App\Filament\Clusters\TrainingManagement\TrainingManagementCluster;
use App\Filament\Resources\RubricResource\Pages\ManageRubrics;
use App\Models\Rubric;

class RubricResource extends InstitutionalResource
{
    protected static ?int $navigationSort = 9;
    protected static ?string $model = Rubric::class;
    protected static ?string $cluster = TrainingManagementCluster::class;
    protected static ?string $modelLabel = 'Rúbrica';
    protected static ?string $pluralModelLabel = 'Rúbricas';
    protected static ?string $recordTitleAttribute = 'name';
    protected static array $formFields = [
        ['name' => 'name', 'label' => 'Nombre', 'required' => true],
        ['name' => 'description', 'label' => 'Descripción', 'type' => 'textarea'],
        ['name' => 'passing_score', 'label' => 'Puntaje de aprobación', 'type' => 'number', 'required' => true],
        ['name' => 'status', 'label' => 'Estado', 'type' => 'select', 'options' => ['activa' => 'Activa', 'inactiva' => 'Inactiva']],
    ];
    protected static array $tableColumns = [
        ['name' => 'name', 'label' => 'Rúbrica', 'searchable' => true],
        ['name' => 'criteria_count', 'label' => 'Criterios'],
        ['name' => 'passing_score', 'label' => 'Aprobación'],
        ['name' => 'status', 'label' => 'Estado'],
    ];
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder { return parent::getEloquentQuery()->withCount('criteria'); }
    public static function getPages(): array { return ['index' => ManageRubrics::route('/')]; }
}
