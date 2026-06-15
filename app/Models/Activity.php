<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use App\Models\Concerns\VisibleToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Activity extends Model
{
    use HasPublicId, SoftDeletes, VisibleToUser;

    protected $guarded = [];

    protected static function booted(): void
    {
        static::creating(function (Activity $activity): void {
            if (! $activity->training_program_id) {
                return;
            }

            $program = TrainingProgram::find($activity->training_program_id);

            if (! $program) {
                return;
            }

            $activity->edition_number ??= ((int) $program->editions()->withTrashed()->max('edition_number')) + 1;
            $activity->name ??= $program->name.' - Edición '.$activity->edition_number;
            $activity->slug ??= $program->slug.'-edicion-'.$activity->edition_number;
            $activity->edition_code ??= Str::upper(Str::slug($program->slug)).'-E'.str_pad((string) $activity->edition_number, 2, '0', STR_PAD_LEFT);
            $activity->activity_type_id ??= $program->activity_type_id;
            $activity->area_id ??= $program->area_id;
            $activity->description ??= $program->description;
            $activity->general_objective ??= $program->general_objective;
            $activity->specific_objectives ??= $program->specific_objectives;
            $activity->skills ??= $program->skills;
            $activity->modality ??= $program->default_modality;
            $activity->language ??= $program->language;
            $activity->duration_hours ??= $program->duration_hours;
            $activity->cost ??= $program->default_cost;
            $activity->is_external ??= $program->is_external;
            $activity->requires_approval ??= $program->requires_approval;
            $activity->requires_payment ??= $program->requires_payment;
            $activity->requires_evaluation ??= $program->requires_evaluation;
            $activity->requires_survey ??= $program->requires_survey;
            $activity->generates_certificate ??= $program->generates_certificate;
            $activity->generates_microcredential ??= $program->generates_microcredential;
            $activity->approval_criteria ??= $program->approval_criteria;
        });
    }

    public function trainingProgram(): BelongsTo
    {
        return $this->belongsTo(TrainingProgram::class);
    }

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
            'enrollment_start_date' => 'date',
            'enrollment_end_date' => 'date',
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
