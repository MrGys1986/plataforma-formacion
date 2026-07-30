<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\TrainingManagement\TrainingManagementCluster;
use App\Filament\Pages\EditionControlPage;
use App\Filament\Resources\ActivityResource\Pages\ManageActivities;
use App\Models\Activity;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;

class ActivityResource extends InstitutionalResource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 2;

    protected static ?string $model = Activity::class;

    protected static ?string $cluster = TrainingManagementCluster::class;

    protected static ?string $modelLabel = 'Edición';

    protected static ?string $pluralModelLabel = 'Ediciones';

    protected static ?string $recordTitleAttribute = 'name';

    protected static bool $softDeletes = true;

    protected static array $tableColumns = [
        [
            'name' => 'trainingProgram.name',
            'label' => 'Programa',
            'searchable' => true,
        ],
        [
            'name' => 'trainingProgram.activityType.name',
            'label' => 'Tipo',
        ],
        [
            'name' => 'edition_number',
            'label' => 'Edición',
        ],
        [
            'name' => 'edition_code',
            'label' => 'Código',
            'searchable' => true,
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
                'label' => 'Curso, minicurso o taller',
                'type' => 'relation',
                'relationship' => 'trainingProgram',
                'required' => true,
                'default' => fn (): ?int => request()->filled('training_program')
                    ? request()->integer('training_program')
                    : null,
                'disabled' => fn (): bool => request()->filled('training_program'),
                'dehydrated' => true,
            ],
            [
                'name' => 'edition_number',
                'label' => 'Número de edición',
                'type' => 'number',
                'required' => true,
            ],
            [
                'name' => 'edition_code',
                'label' => 'Código de edición',
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
                'name' => 'max_capacity',
                'label' => 'Cupo máximo',
                'type' => 'number',
            ],
            [
                'name' => 'cost',
                'label' => 'Costo de esta edición',
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

    /**
     * @return array<Action|\Filament\Actions\ActionGroup>
     */
    protected static function getTableRecordActions(): array
    {
        return [
            Action::make('control')
                ->label('Gestionar edición')
                ->icon(Heroicon::OutlinedArrowRight)
                ->color('primary')
                ->url(fn (Activity $record): string => EditionControlPage::getUrl(['record' => $record->getKey()])),
        ];
    }
}
