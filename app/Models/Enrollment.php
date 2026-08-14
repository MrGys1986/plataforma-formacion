<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use App\Models\Concerns\VisibleToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enrollment extends Model
{
    use HasPublicId, VisibleToUser;

    protected $guarded = [];

    protected static function booted(): void
    {
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
