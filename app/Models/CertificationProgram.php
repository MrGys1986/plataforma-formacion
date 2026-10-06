<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use App\Models\Concerns\VisibleToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CertificationProgram extends Model
{
    use HasPublicId, SoftDeletes, VisibleToUser;

    protected $guarded = [];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function competencies(): BelongsToMany
    {
        return $this->belongsToMany(Competency::class, 'competency_certification')
            ->withPivot(['order_number', 'is_required'])
            ->withTimestamps();
    }

    public function diplomaPrograms(): BelongsToMany
    {
        return $this->belongsToMany(DiplomaProgram::class, 'certification_diploma')
            ->withPivot(['order_number', 'is_required'])
            ->withTimestamps();
    }

    public function trainingPrograms(): BelongsToMany
    {
        return $this->belongsToMany(TrainingProgram::class, 'certification_training_program')
            ->withPivot(['order_number', 'is_required', 'minimum_score'])
            ->withTimestamps()
            ->orderByPivot('order_number');
    }

    public function learningPaths(): BelongsToMany
    {
        return $this->belongsToMany(LearningPath::class, 'learning_path_certification_program')
            ->withPivot(['order_number', 'is_required'])
            ->withTimestamps();
    }

    protected function casts(): array
    {
        return ['generates_microcredential' => 'boolean'];
    }
}
