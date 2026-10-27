<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\LearningPathManagement\LearningPathManagementCluster;
use App\Filament\Resources\LearningPathResource;
use App\Models\LearningPath;
use App\Models\TrainingProgram;
use App\Services\LearningPaths\LearningPathProgressService;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class LearningPathControlPage extends Page
{
    protected static ?string $cluster = LearningPathManagementCluster::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'ruta';

    protected string $view = 'filament.pages.learning-path-control';

    /** @var LearningPath */
    public $record;

    public function mount(int|string $record): void
    {
        $routeColumn = ctype_digit((string) $record)
            ? (new LearningPath)->getKeyName()
            : (new LearningPath)->getRouteKeyName();

        $this->record = LearningPath::query()
            ->visibleTo(auth()->user())
            ->with(['area'])
            ->where($routeColumn, $record)
            ->first();

        if (! $this->record) {
            abort_unless(LearningPath::onlyTrashed()
                ->visibleTo(auth()->user())
                ->where($routeColumn, $record)
                ->exists(), 404);

            Notification::make()
                ->title('Esta ruta fue eliminada. Selecciona una ruta disponible.')
                ->warning()
                ->send();

            $this->redirect(LearningPathResource::getUrl());

            return;
        }

        Gate::authorize('view', $this->record);
    }

    public static function getRoutePath(Panel $panel): string
    {
        return '/ruta/{record}';
    }

    public function getHeading(): ?string
    {
        return null;
    }

    public function getSubheading(): ?string
    {
        return null;
    }

    public function getSubNavigation(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function manageProgramsAction(): Action
    {
        return Action::make('managePrograms')
            ->label('Administrar')
            ->icon(Heroicon::OutlinedAdjustmentsHorizontal)
            ->authorize(fn (): bool => Gate::allows('update', $this->record) && ! $this->record->trashed())
            ->modalHeading('Administrar cursos, minicursos y talleres')
            ->modalDescription($this->record->name)
            ->modalWidth(Width::FourExtraLarge)
            ->modalContent(fn () => view('filament.partials.learning-path-programs', [
                'programs' => $this->record->trainingPrograms()->with('activityType')->get(),
            ]))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Cerrar')
            ->extraModalFooterActions([
                $this->addMiniCoursesAction(),
                $this->addCoursesAction(),
                $this->addWorkshopsAction(),
            ]);
    }

    public function addMiniCoursesAction(): Action
    {
        return $this->programAttachmentAction('addMiniCourses', 'Minicurso', 'minicursos');
    }

    public function addCoursesAction(): Action
    {
        return $this->programAttachmentAction('addCourses', 'Curso', 'cursos');
    }

    public function addWorkshopsAction(): Action
    {
        return $this->programAttachmentAction('addWorkshops', 'Taller', 'talleres');
    }

    private function availablePrograms(string $type): Builder
    {
        return TrainingProgram::query()
            ->visibleTo(auth()->user())
            ->where('status', 'activo')
            ->whereHas('activityType', fn (Builder $query): Builder => $query->where('name', $type))
            ->whereDoesntHave('learningPaths', fn (Builder $query): Builder => $query->whereKey($this->record->id));
    }

    private function programAttachmentAction(string $name, string $type, string $label): Action
    {
        return Action::make($name)
            ->label('Agregar '.$label)
            ->authorize(fn (): bool => Gate::allows('update', $this->record) && ! $this->record->trashed())
            ->modalHeading('Agregar '.$label.' a la ruta')
            ->modalDescription('Selecciona los elementos del catálogo que quieres agregar. Puedes elegir varios.')
            ->modalSubmitActionLabel('Agregar a la ruta')
            ->modalCancelActionLabel('Cancelar')
            ->schema([
                Select::make('program_ids')
                    ->label(ucfirst($label))
                    ->options(fn (): array => $this->availablePrograms($type)->orderBy('name')->pluck('name', 'id')->all())
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->noOptionsMessage('No hay '.$label.' disponibles para agregar.')
                    ->required(),
                TextInput::make('order_number')
                    ->label('Orden inicial')
                    ->helperText('Los elementos seleccionados se agregarán en posiciones consecutivas.')
                    ->numeric()
                    ->integer()
                    ->minValue(1)
                    ->default(fn (): int => max(
                        (int) $this->record->trainingPrograms()->max('learning_path_training_program.order_number'),
                        (int) $this->record->diplomaPrograms()->max('learning_path_diploma_program.order_number'),
                        (int) $this->record->certificationPrograms()->max('learning_path_certification_program.order_number'),
                        (int) $this->record->items()->max('order_number'),
                    ) + 1)
                    ->required(),
                Select::make('is_required')
                    ->label('Aplicación')
                    ->options([1 => 'Obligatorio', 0 => 'Opcional'])
                    ->default(1)
                    ->native(false)
                    ->required(),
                TextInput::make('minimum_score')
                    ->label('Calificación mínima')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),
            ])
            ->databaseTransaction()
            ->action(function (array $data, Action $action) use ($type): void {
                $path = LearningPath::query()->visibleTo(auth()->user())->lockForUpdate()->findOrFail($this->record->id);
                Gate::authorize('update', $path);

                $programIds = collect($data['program_ids'])->unique()->values();

                if ($this->availablePrograms($type)->whereKey($programIds)->count() !== $programIds->count()) {
                    throw ValidationException::withMessages([
                        $this->getMountedActionSchema()->getStatePath().'.program_ids' => 'Selecciona elementos disponibles de este tipo que aún no estén en la ruta.',
                    ]);
                }

                $links = $programIds->mapWithKeys(fn ($id, int $index): array => [$id => [
                    'order_number' => (int) $data['order_number'] + $index,
                    'is_required' => (bool) $data['is_required'],
                    'minimum_score' => $data['minimum_score'] ?? null,
                ]])->all();

                $path->trainingPrograms()->syncWithoutDetaching($links);
                $path->userLearningPaths()->each(fn ($assignment) => app(LearningPathProgressService::class)->synchronizeAssignment($assignment));
                $this->record = $path->fresh(['area']);
                $action->success();
            })
            ->successNotificationTitle('Programas agregados a la ruta');
    }

    protected function getViewData(): array
    {
        $this->record->loadCount([
            'trainingPrograms', 'diplomaPrograms', 'certificationPrograms', 'userLearningPaths', 'items',
            'trainingPrograms as mini_courses_count' => fn (Builder $query): Builder => $query->whereHas('activityType', fn (Builder $types): Builder => $types->where('name', 'Minicurso')),
            'trainingPrograms as courses_count' => fn (Builder $query): Builder => $query->whereHas('activityType', fn (Builder $types): Builder => $types->where('name', 'Curso')),
            'trainingPrograms as workshops_count' => fn (Builder $query): Builder => $query->whereHas('activityType', fn (Builder $types): Builder => $types->where('name', 'Taller')),
        ]);

        return [
            'learningPath' => $this->record,
            'pathsUrl' => LearningPathResource::getUrl(),
            'editUrl' => LearningPathResource::getUrl('edit', [
                'record' => $this->record,
            ]),
            'breadcrumbs' => [
                [
                    'label' => 'Rutas de aprendizaje',
                    'url' => LearningPathResource::getUrl(),
                ],
                [
                    'label' => $this->record->name,
                    'url' => null,
                ],
            ],
            'cards' => [
                [
                    'title' => 'Diplomados',
                    'description' => 'Vincula diplomados que agrupan cursos, minicursos y talleres.',
                    'count' => $this->record->diploma_programs_count,
                    'url' => LearningPathResource::getUrl('edit', ['record' => $this->record, 'relation' => '0']),
                    'icon' => Heroicon::OutlinedAcademicCap,
                ],
                [
                    'title' => 'Certificaciones',
                    'description' => 'Vincula certificaciones con diplomados y programas.',
                    'count' => $this->record->certification_programs_count,
                    'url' => LearningPathResource::getUrl('edit', ['record' => $this->record, 'relation' => '1']),
                    'icon' => Heroicon::OutlinedCheckBadge,
                ],
                [
                    'title' => 'Programas de formación',
                    'description' => 'Agrega cursos, minicursos o talleres y define su orden.',
                    'count' => $this->record->training_programs_count,
                    'action' => 'managePrograms',
                    'icon' => Heroicon::OutlinedAcademicCap,
                ],
                [
                    'title' => 'Participantes',
                    'description' => 'Asigna personas y consulta su avance dentro de la ruta.',
                    'count' => $this->record->user_learning_paths_count,
                    'url' => LearningPathResource::getUrl('edit', [
                        'record' => $this->record,
                        'relation' => '3',
                    ]),
                    'icon' => Heroicon::OutlinedUsers,
                ],
                [
                    'title' => 'Actividades anteriores',
                    'description' => 'Consulta las actividades creadas antes del catálogo de programas.',
                    'count' => $this->record->items_count,
                    'url' => LearningPathResource::getUrl('edit', [
                        'record' => $this->record,
                        'relation' => '4',
                    ]),
                    'icon' => Heroicon::OutlinedClock,
                ],
            ],
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
