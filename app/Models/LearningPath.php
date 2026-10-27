<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use App\Models\Concerns\VisibleToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LearningPath extends Model
{
    use HasPublicId, SoftDeletes, VisibleToUser;

    protected $guarded = [];

    protected static function booted(): void
    {
        static::saving(function (LearningPath $learningPath): void {
            if (blank($learningPath->path_type)) {
                $learningPath->path_type = 'ruta';
            }
            $learningPath->generates_microcredential ??= $learningPath->path_type === 'microcredencial';
        });
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function coverFile(): BelongsTo
    {
        return $this->belongsTo(FileUpload::class, 'cover_file_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(LearningPathItem::class)->orderBy('order_number');
    }

    public function userLearningPaths(): HasMany
    {
        return $this->hasMany(UserLearningPath::class);
    }

    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'learning_path_items')
            ->withPivot(['order_number', 'is_required', 'minimum_score'])
            ->withTimestamps()
            ->orderByPivot('order_number');
    }

    public function trainingPrograms(): BelongsToMany
    {
        return $this->belongsToMany(TrainingProgram::class, 'learning_path_training_program')
            ->withPivot(['order_number', 'is_required', 'minimum_score'])
            ->withTimestamps()
            ->orderByPivot('order_number');
    }

    public function diplomaPrograms(): BelongsToMany
    {
        return $this->belongsToMany(DiplomaProgram::class, 'learning_path_diploma_program')
            ->withPivot(['order_number', 'is_required'])
            ->withTimestamps()
            ->orderByPivot('order_number');
    }

    public function certificationPrograms(): BelongsToMany
    {
        return $this->belongsToMany(CertificationProgram::class, 'learning_path_certification_program')
            ->withPivot(['order_number', 'is_required'])
            ->withTimestamps()
            ->orderByPivot('order_number');
    }

    public function competencyDefinitions(): BelongsToMany
    {
        return $this->belongsToMany(Competency::class, 'learning_path_competency')
            ->withPivot(['order_number', 'is_required'])
            ->withTimestamps()
            ->orderByPivot('order_number');
    }

    protected function casts(): array
    {
        return [
            'total_hours' => 'decimal:2',
            'is_sequential' => 'boolean',
            'generates_diploma' => 'boolean',
            'generates_microcredential' => 'boolean',
        ];
    }
}
