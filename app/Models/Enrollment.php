<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use App\Models\Concerns\VisibleToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;

class Enrollment extends Model
{
    use HasPublicId, VisibleToUser;

    protected $guarded = [];

    public const CAPACITY_STATUSES = ['solicitada', 'aprobada'];

    public function save(array $options = []): bool
    {
        if ($this->exists && ! $this->isDirty(['user_id', 'activity_id', 'status'])) {
            return parent::save($options);
        }

        // Serializa la comprobación y el guardado de inscripciones de la misma actividad.
        return $this->getConnection()->transaction(function () use ($options): bool {
            $activity = Activity::withTrashed()->whereKey($this->activity_id)->lockForUpdate()->first();
            $errors = $this->availabilityErrors($activity);

            if ($errors !== []) {
                throw ValidationException::withMessages($errors);
            }

            return parent::save($options);
        });
    }

    /** @return array<string, string> */
    public function availabilityErrors(?Activity $activity = null): array
    {
        $activity ??= Activity::withTrashed()->find($this->activity_id);
        $others = $this->newQuery()->where('activity_id', $this->activity_id)
            ->when($this->exists, fn ($query) => $query->whereKeyNot($this->getKey()));

        if ((clone $others)->where('user_id', $this->user_id)->exists()) {
            return ['user_id' => 'Este alumno ya tiene una inscripción en esta actividad.'];
        }

        $previous = $this->exists ? $this->newQuery()->find($this->getKey()) : null;
        $alreadyOccupiesPlace = $previous && (int) $previous->activity_id === (int) $this->activity_id
            && in_array($previous->status, self::CAPACITY_STATUSES, true);

        if ($activity?->max_capacity !== null && ! $alreadyOccupiesPlace
            && in_array($this->status ?? 'solicitada', self::CAPACITY_STATUSES, true)
            && (clone $others)->whereIn('status', self::CAPACITY_STATUSES)->count() >= $activity->max_capacity) {
            return ['activity_id' => 'No hay cupos disponibles en esta actividad.'];
        }

        return [];
    }

    protected static function booted(): void
    {
        static::saving(function (Enrollment $enrollment): void {
            if ($enrollment->isDirty('completion_status')) {
                $enrollment->completed_at = $enrollment->completion_status === 'completado'
                    ? ($enrollment->completed_at ?? now())
                    : null;
            }
        });

        static::updating(function (Enrollment $enrollment): void {
            if (! $enrollment->isDirty('status')) {
                return;
            }

            if ($enrollment->status === 'aprobada') {
                $enrollment->approved_by ??= auth()->id();
                $enrollment->approved_at ??= now();
                $enrollment->rejection_reason = null;
            }

            if ($enrollment->status !== 'aprobada') {
                $enrollment->approved_by = null;
                $enrollment->approved_at = null;
            }
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

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function evidences(): HasMany
    {
        return $this->hasMany(Evidence::class);
    }

    public function evaluationResults(): HasMany
    {
        return $this->hasMany(EvaluationResult::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    protected function casts(): array
    {
        return [
            'requested_at' => 'datetime',
            'approved_at' => 'datetime',
            'final_score' => 'decimal:2',
            'completed_at' => 'datetime',
        ];
    }
}
