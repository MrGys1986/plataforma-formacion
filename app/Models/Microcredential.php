<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use App\Models\Concerns\VisibleToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;

class Microcredential extends Model
{
    use HasPublicId, VisibleToUser;

    protected $guarded = [];

    protected static function booted(): void
    {
        static::saving(function (Microcredential $badge): void {
            $sources = ['activity_id', 'learning_path_id', 'diploma_program_id', 'certification_program_id'];

            if (! $badge->exists || $badge->isDirty($sources)) {
                $selected = collect($sources)->filter(fn (string $column): bool => filled($badge->getAttribute($column)));

                if ($selected->count() > 1) {
                    throw ValidationException::withMessages([
                        'activity_id' => 'Selecciona solo una fuente: actividad, ruta, diplomado o certificación.',
                    ]);
                }
            }

            if ($badge->status === 'validada') {
                $badge->issued_at ??= now();
            }
        });

        static::created(function (Microcredential $badge): void {
            if ($badge->json_payload === null) {
                $badge->load(['user', 'activity', 'learningPath', 'diplomaProgram', 'certificationProgram']);
                $badge->forceFill([
                    'json_payload' => app(\App\Services\Microcredentials\MicrocredentialPayloadService::class)->buildPayload($badge),
                ])->saveQuietly();
            }
        });

        static::updated(function (Microcredential $badge): void {
            if (! $badge->wasChanged(['name', 'description', 'skills', 'competencies', 'user_id', 'activity_id', 'learning_path_id', 'diploma_program_id', 'certification_program_id'])) {
                return;
            }

            $badge->load(['user', 'activity', 'learningPath', 'diplomaProgram', 'certificationProgram']);
            $badge->forceFill([
                'json_payload' => app(\App\Services\Microcredentials\MicrocredentialPayloadService::class)->buildPayload($badge),
            ])->saveQuietly();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function learningPath(): BelongsTo
    {
        return $this->belongsTo(LearningPath::class);
    }

    public function diplomaProgram(): BelongsTo
    {
        return $this->belongsTo(DiplomaProgram::class);
    }

    public function certificationProgram(): BelongsTo
    {
        return $this->belongsTo(CertificationProgram::class);
    }

    public function certificate(): BelongsTo
    {
        return $this->belongsTo(Certificate::class);
    }

    protected function casts(): array
    {
        return [
            'json_payload' => 'array',
            'external_response' => 'array',
            'issued_at' => 'datetime',
        ];
    }
}
