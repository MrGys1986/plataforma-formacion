<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use App\Models\Concerns\VisibleToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    use HasPublicId, VisibleToUser;

    protected $guarded = [];

    public function activityType(): BelongsTo
    {
        return $this->belongsTo(ActivityType::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function evidences(): HasMany
    {
        return $this->hasMany(Evidence::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function microcredentials(): HasMany
    {
        return $this->hasMany(Microcredential::class);
    }

    public function learningPathItems(): HasMany
    {
        return $this->hasMany(LearningPathItem::class);
    }

    public function learningPaths(): BelongsToMany
    {
        return $this->belongsToMany(LearningPath::class, 'learning_path_items')
            ->withPivot(['order_number', 'is_required', 'minimum_score'])
            ->withTimestamps();
    }

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'duration_hours' => 'decimal:2',
            'cost' => 'decimal:2',
            'is_external' => 'boolean',
            'requires_approval' => 'boolean',
            'requires_payment' => 'boolean',
            'requires_evaluation' => 'boolean',
            'requires_survey' => 'boolean',
            'generates_certificate' => 'boolean',
            'generates_microcredential' => 'boolean',
        ];
    }
}
