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
    protected static bool $shouldRegisterNavigation = true;

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
        return $query;
    }

    /**
     * @return array<Action|\Filament\Actions\ActionGroup>
     */
    protected static function getTableRecordActions(): array
    {
        return [
            Action::make('control')
                ->label('Administrar')
                ->icon(Heroicon::OutlinedArrowRight)
                ->color('primary')
                ->url(fn (Activity $record): string => EditionControlPage::getUrl(['record' => $record->getKey()])),
        ];
    }
}
