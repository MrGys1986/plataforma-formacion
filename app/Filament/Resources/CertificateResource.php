<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\CredentialManagement\CredentialManagementCluster;
use App\Filament\Resources\CertificateResource\Pages\ManageCertificates;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Services\Files\ManagedFileService;
use Closure;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CertificateResource extends InstitutionalResource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 2;

    protected static ?string $model = Certificate::class;

    protected static ?string $cluster = CredentialManagementCluster::class;

    protected static ?string $modelLabel = 'Constancia';

    protected static ?string $pluralModelLabel = 'Constancias';

    protected static ?string $recordTitleAttribute = 'folio';

    protected static array $tableColumns = [
        [
            'name' => 'folio',
            'label' => 'Folio',
            'searchable' => true,
        ],
        [
            'name' => 'user.name',
            'label' => 'Titular',
            'searchable' => true,
        ],
        [
            'name' => 'activity.trainingProgram.name',
            'label' => 'Programa de formación',
        ],
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
            'index' => ManageCertificates::route('/'),
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('enrollment_id')->label('Participante que completó la formación')
                ->relationship('enrollment', 'id', modifyQueryUsing: fn (Builder $query): Builder => static::eligibleEnrollments($query))
                ->getOptionLabelFromRecordUsing(fn (Enrollment $record): string => $record->user->name.' · '.$record->activity->participant_name)
                ->getSearchResultsUsing(fn (string $search): array => static::eligibleEnrollments(Enrollment::query())
                    ->whereHas('user', fn (Builder $users): Builder => $users
                        ->where('name', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%'))
                    ->limit(50)->get()->mapWithKeys(fn (Enrollment $enrollment): array => [
                        $enrollment->id => $enrollment->user->name.' · '.$enrollment->activity->participant_name,
                    ])->all())
                ->searchable()->preload()->required()
                ->rules([
                    fn (?Certificate $record): Closure => function (string $attribute, mixed $value, Closure $fail) use ($record): void {
                        $enrollment = static::eligibleEnrollments(Enrollment::query())->find($value);

                        if (! $enrollment || ($record && $record->enrollment_id !== $enrollment->id)) {
                            $fail('Selecciona una inscripción aprobada y completada de este programa.');
                        } elseif (Certificate::where('enrollment_id', $enrollment->id)
                            ->when($record, fn (Builder $query): Builder => $query->whereKeyNot($record->id))->exists()) {
                            $fail('Este participante ya tiene constancia. Edita la existente para reemplazarla.');
                        }
                    },
                ])
                ->disabled(fn (?Certificate $record): bool => $record !== null)->dehydrated(),
            TextInput::make('folio')->label('Folio')->required()->maxLength(255)->unique(ignoreRecord: true)
                ->default(fn (): string => 'CONST-'.now()->format('Y').'-'.Str::upper(Str::random(12))),
            static::makeFormComponent([
                'name' => 'file_upload_id',
                'label' => 'Constancia o certificado',
                'type' => 'file',
                'directory' => 'certificates',
                'accepted_types' => ['application/pdf'],
                'required' => true,
            ])->helperText('Solo PDF. Tamaño máximo: '.(config('security.upload_max_kilobytes') / 1024).' MB.'),
        ]);
    }

    private static function eligibleEnrollments(Builder $query): Builder
    {
        $query->visibleTo(auth()->user())->with(['user', 'activity'])
            ->where('status', 'aprobada')->where('completion_status', 'completado')
            ->whereHas('activity', fn (Builder $activity): Builder => $activity->where('generates_certificate', true));

        if (request()->filled('training_program')) {
            $query->whereHas('activity', fn (Builder $activity): Builder => $activity->where('training_program_id', request()->integer('training_program')));
        } elseif (request()->filled('activity')) {
            $query->where('activity_id', request()->integer('activity'));
        }

        return $query;
    }

    public static function prepareCertificateData(array $data, ?Certificate $record = null): array
    {
        $enrollment = static::eligibleEnrollments(Enrollment::query())->find($data['enrollment_id'] ?? null);

        if (! $enrollment || ($record && $record->enrollment_id !== $enrollment->id)) {
            throw ValidationException::withMessages(['enrollment_id' => 'Selecciona una inscripción aprobada y completada de este programa.']);
        }

        if (Certificate::query()->where('enrollment_id', $enrollment->id)
            ->when($record, fn (Builder $query): Builder => $query->whereKeyNot($record->id))->exists()) {
            throw ValidationException::withMessages(['enrollment_id' => 'Este participante ya tiene constancia. Edita la existente para reemplazarla.']);
        }

        return [
            ...$data,
            'user_id' => $enrollment->user_id,
            'activity_id' => $enrollment->activity_id,
            'certificate_type' => 'terminacion',
            'issued_by' => auth()->id(),
            'issued_at' => $record?->issued_at ?? now(),
            'status' => 'emitida',
        ];
    }

    protected static function modifyTable(Table $table): Table
    {
        return $table->recordActions([
            EditAction::make()
                ->mutateDataUsing(fn (array $data, Certificate $record): array => static::prepareCertificateData($data, $record))
                ->using(function (Certificate $record, array $data): Certificate {
                    $previousFile = $record->fileUpload;
                    $record->update($data);

                    if ($previousFile && $previousFile->id !== $record->file_upload_id) {
                        app(ManagedFileService::class)->scheduleDeletion($previousFile);
                    }

                    return $record;
                }),
        ]);
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
}
