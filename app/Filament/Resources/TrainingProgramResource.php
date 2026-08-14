<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\TrainingManagement\TrainingManagementCluster;
use App\Filament\Resources\TrainingProgramResource\Pages\ManageTrainingPrograms;
use App\Models\TrainingProgram;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\ResourceConfiguration;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class TrainingProgramResource extends InstitutionalResource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $configurationClass = ResourceConfiguration::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $model = TrainingProgram::class;

    protected static ?string $cluster = TrainingManagementCluster::class;

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
            'name' => 'editions_count',
            'label' => 'Ediciones',
        ],
        [
            'name' => 'status',
            'label' => 'Estado',
        ],
    ];

    public static function getNavigationLabel(): string
    {
        $configuredType = static::getConfiguredType();

        return $configuredType['label'] ?? 'Oferta formativa';
    }

    public static function getNavigationParentItem(): ?string
    {
        return static::getConfiguration()?->getKey() ? 'Oferta formativa' : null;
    }

    public static function getNavigationSort(): ?int
    {
        $configuredType = static::getConfiguredType();

        return $configuredType['sort'] ?? parent::getNavigationSort();
    }

    public static function getNavigationIcon(): string|\BackedEnum|Htmlable|null
    {
        return static::getConfiguration()?->getKey() ? null : Heroicon::OutlinedAcademicCap;
    }

    public static function getPluralModelLabel(): string
    {
        $configuredType = static::getConfiguredType();

        return $configuredType['label'] ?? 'Oferta formativa';
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->withCount('editions');
        $configuredType = static::getConfiguredType();

        if (! $configuredType) {
            return $query;
        }

        return $query->whereHas(
            'activityType',
            fn (Builder $activityTypes): Builder => $activityTypes->where('name', $configuredType['type']),
        );
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTrainingPrograms::route('/'),
        ];
    }

    public static function getConfigurationKeyForType(?string $type): ?string
    {
        $configuredType = collect(static::getTypeConfigurations())
            ->first(fn (array $config): bool => $config['type'] === $type);

        return $configuredType['key'] ?? null;
    }

    public static function getBrowseUrlForProgram(?TrainingProgram $program = null): string
    {
        return static::getUrl(
            configuration: static::getConfigurationKeyForType($program?->activityType?->name),
        );
    }

    /**
     * @return array<Action|ActionGroup>
     */
    protected static function getTableRecordActions(): array
    {
        return [
            Action::make('ediciones')
                ->label('Ediciones')
                ->icon(Heroicon::OutlinedRectangleStack)
                ->color('primary')
                ->url(
                    fn (TrainingProgram $record): string => ActivityResource::getUrl(
                        parameters: ['training_program' => $record->id],
                    ),
                ),
        ];
    }

    protected static function getConfiguredType(): ?array
    {
        return collect(static::getTypeConfigurations())
            ->firstWhere('key', static::getConfiguration()?->getKey());
    }

    protected static function getTypeConfigurations(): array
    {
        return [
            [
                'key' => 'cursos',
                'label' => 'Cursos',
                'type' => 'Curso',
                'sort' => 1,
            ],
            [
                'key' => 'minicursos',
                'label' => 'Minicursos',
                'type' => 'Minicurso',
                'sort' => 2,
            ],
            [
                'key' => 'talleres',
                'label' => 'Talleres',
                'type' => 'Taller',
                'sort' => 3,
            ],
        ];
    }
}
