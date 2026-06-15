<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use App\Models\Concerns\VisibleToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingProgram extends Model
{
    use HasPublicId, SoftDeletes, VisibleToUser;

    protected $guarded = [];

    public function activityType(): BelongsTo
    {
        return $this->belongsTo(ActivityType::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editions(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function enrollments(): HasManyThrough
    {
        return $this->hasManyThrough(
            Enrollment::class,
            Activity::class,
            'training_program_id',
            'activity_id',
        );
    }

    public function diplomaPrograms(): BelongsToMany
    {
        return $this->belongsToMany(DiplomaProgram::class, 'diploma_training_program')
            ->withPivot(['order_number', 'is_required', 'minimum_score'])
            ->withTimestamps();
    }

    public function surveys(): BelongsToMany
    {
        return $this->belongsToMany(Survey::class)->withTimestamps();
    }

    protected function casts(): array
    {
        return [
            'duration_hours' => 'decimal:2',
            'default_cost' => 'decimal:2',
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
