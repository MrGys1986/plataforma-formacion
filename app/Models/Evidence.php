<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use App\Models\Concerns\VisibleToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evidence extends Model
{
    use HasPublicId, VisibleToUser;

    protected $table = 'evidences';

    protected $guarded = [];

    protected static function booted(): void
    {
        static::updating(function (Evidence $evidence): void {
            if (! $evidence->isDirty('status')) {
                return;
            }

            if ($evidence->status === 'validada') {
                $evidence->validated_by ??= auth()->id();
                $evidence->validated_at ??= now();
                $evidence->rejection_reason = null;
            }

            if ($evidence->status !== 'validada') {
                $evidence->validated_by = null;
                $evidence->validated_at = null;
            }

            if ($evidence->status !== 'rechazada') {
                $evidence->rejection_reason = null;
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

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function fileUpload(): BelongsTo
    {
        return $this->belongsTo(FileUpload::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function assignedEvaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_evaluator_id');
    }

    public function validatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(EvidenceReview::class);
    }

    protected function casts(): array
    {
        return [
            'validated_at' => 'datetime',
        ];
    }
}
