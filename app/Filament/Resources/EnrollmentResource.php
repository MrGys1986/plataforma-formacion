<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\TrainingManagement\TrainingManagementCluster;
use App\Filament\Resources\EnrollmentResource\Pages\ManageEnrollments;
use App\Models\Enrollment;
use App\Services\Enrollments\EnrollmentService;
use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;

class EnrollmentResource extends InstitutionalResource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 4;

    protected static ?string $model = Enrollment::class;

    protected static ?string $cluster = TrainingManagementCluster::class;

    protected static ?string $modelLabel = 'Inscripción';

    protected static ?string $pluralModelLabel = 'Inscripciones';

    protected static ?string $recordTitleAttribute = 'id';

    protected static array $tableColumns = [
        [
            'name' => 'user.name',
            'label' => 'Participante',
            'searchable' => true,
        ],
        [
            'name' => 'activity.trainingProgram.name',
            'label' => 'Programa de formación',
            'searchable' => true,
        ],
        [
            'name' => 'status',
            'label' => 'Estado',
        ],
        [
            'name' => 'completion_status',
            'label' => 'Finalización',
        ],
    ];

    protected static ?string $statusColumn = 'status';

    protected static bool $readOnly = false;

    public static function getPages(): array
    {
        return [
            'index' => ManageEnrollments::route('/'),
        ];
    }

    protected static function getFormFields(): array
    {
        return [
            [
                'name' => 'user_id',
                'label' => 'Participante',
                'type' => 'relation',
                'relationship' => 'user',
                'required' => true,
            ],
            [
                'name' => 'activity_id',
                'label' => 'Programa de formación',
                'type' => 'relation',
                'relationship' => 'activity',
                'required' => true,
                'default' => fn (): ?int => request()->filled('activity')
                    ? request()->integer('activity')
                    : null,
                'disabled' => fn (): bool => request()->filled('activity'),
                'dehydrated' => true,
            ],
            [
                'name' => 'status',
                'label' => 'Estado',
                'type' => 'select',
                'required' => true,
                'options' => [
                    'solicitada' => 'Pendiente de revisión',
                    'aprobada' => 'Aprobada',
                    'rechazada' => 'Rechazada',
                    'cancelada' => 'Cancelada',
                ],
            ],
            [
                'name' => 'payment_status',
                'label' => 'Estado de pago',
                'type' => 'select',
                'required' => true,
                'options' => [
                    'no_aplica' => 'No aplica',
                    'pendiente' => 'Pendiente de validación',
                    'aprobado' => 'Pago aprobado',
                    'rechazado' => 'Pago rechazado',
                ],
            ],
            [
                'name' => 'completion_status',
                'label' => 'Estado de finalización',
                'type' => 'select',
                'required' => true,
                'options' => [
                    'no_iniciado' => 'No iniciado',
                    'en_progreso' => 'En progreso',
                    'completado' => 'Completado',
                    'no_aprobado' => 'No aprobado',
                ],
            ],
            [
                'name' => 'final_score',
                'label' => 'Calificación final',
                'type' => 'number',
                'min' => 0,
                'max' => 100,
                'step' => 0.01,
            ],
        ];
    }

    protected static function applyContextToQuery(Builder $query): Builder
    {
        if (request()->filled('training_program')) {
            return $query->whereHas(
                'activity',
                fn (Builder $activity): Builder => $activity->where(
                    'training_program_id',
                    request()->integer('training_program'),
                ),
            );
        }

        if (! request()->filled('activity')) {
            return $query;
        }

        return $query->where('activity_id', request()->integer('activity'));
    }

    protected static function getTableRecordActions(): array
    {
        return [
            Action::make('approve')->label('Aprobar')
                ->visible(fn (Enrollment $record): bool => $record->status === 'solicitada' && Gate::allows('approve', $record))
                ->action(fn (Enrollment $record) => app(EnrollmentService::class)->approve($record, auth()->user())),
            Action::make('reject')->label('Rechazar')->color('danger')
                ->visible(fn (Enrollment $record): bool => $record->status === 'solicitada' && Gate::allows('reject', $record))
                ->schema([Textarea::make('reason')->label('Motivo')->required()->maxLength(1000)])
                ->action(fn (Enrollment $record, array $data) => app(EnrollmentService::class)->reject($record, $data['reason'])),
        ];
    }

    protected static function makeFormComponent(array $definition): Component
    {
        $component = parent::makeFormComponent($definition);

        if (in_array($definition['name'], ['user_id', 'activity_id'], true)) {
            $field = $definition['name'];
            $component->rules([
                fn (Get $get, ?Enrollment $record): Closure => function (string $attribute, mixed $value, Closure $fail) use ($get, $record, $field): void {
                    if (blank($get('user_id')) || blank($get('activity_id'))) {
                        return;
                    }

                    $candidate = $record ? clone $record : new Enrollment;
                    $candidate->fill([
                        'user_id' => $get('user_id'),
                        'activity_id' => $get('activity_id'),
                        'status' => $get('status') ?? 'solicitada',
                    ]);
                    $message = $candidate->availabilityErrors()[$field] ?? null;

                    if ($message !== null) {
                        $fail($message);
                    }
                },
            ]);
        }

        if ($definition['name'] === 'completion_status') {
            $component->rules([
                fn (Get $get): Closure => function (string $attribute, mixed $value, Closure $fail) use ($get): void {
                    if ($value === 'completado' && ($get('status') !== 'aprobada' || blank($get('final_score')))) {
                        $fail('Aprueba la inscripción y captura la calificación antes de marcarla como completada.');
                    }
                },
            ]);
        }

        return $component;
    }
}
