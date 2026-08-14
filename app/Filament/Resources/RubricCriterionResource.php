<?php
namespace App\Filament\Resources;
use App\Filament\Clusters\TrainingManagement\TrainingManagementCluster;
use App\Filament\Resources\RubricCriterionResource\Pages\ManageRubricCriteria;
use App\Models\RubricCriterion;

class RubricCriterionResource extends InstitutionalResource
{
    protected static ?int $navigationSort = 10;
    protected static ?string $model = RubricCriterion::class;
    protected static ?string $cluster = TrainingManagementCluster::class;
    protected static ?string $modelLabel = 'Criterio de rúbrica';
    protected static ?string $pluralModelLabel = 'Criterios de rúbricas';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $statusColumn = null;
    protected static array $formFields = [
        ['name' => 'rubric_id', 'label' => 'Rúbrica', 'type' => 'relation', 'relationship' => 'rubric', 'required' => true],
        ['name' => 'name', 'label' => 'Criterio', 'required' => true],
        ['name' => 'description', 'label' => 'Descripción', 'type' => 'textarea'],
        ['name' => 'weight', 'label' => 'Ponderación (%)', 'type' => 'number', 'required' => true],
        ['name' => 'max_points', 'label' => 'Puntos máximos', 'type' => 'number', 'required' => true],
        ['name' => 'sort_order', 'label' => 'Orden', 'type' => 'number', 'required' => true],
    ];
    protected static array $tableColumns = [
        ['name' => 'rubric.name', 'label' => 'Rúbrica'],
        ['name' => 'name', 'label' => 'Criterio', 'searchable' => true],
        ['name' => 'weight', 'label' => 'Ponderación'],
        ['name' => 'max_points', 'label' => 'Puntos'],
        ['name' => 'sort_order', 'label' => 'Orden'],
    ];
    public static function getPages(): array { return ['index' => ManageRubricCriteria::route('/')]; }
}
