<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use App\Models\Concerns\VisibleToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Competency extends Model
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

    public function learningPaths(): BelongsToMany
    {
        return $this->belongsToMany(LearningPath::class, 'learning_path_competency')
            ->withPivot(['order_number', 'is_required'])
            ->withTimestamps();
    }

    public function certificationPrograms(): BelongsToMany
    {
        return $this->belongsToMany(CertificationProgram::class, 'competency_certification')
            ->withPivot(['order_number', 'is_required'])
            ->withTimestamps();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_competencies')
            ->withPivot(['status', 'progress_percentage', 'unlocked_at', 'completed_at'])
            ->withTimestamps();
    }
}
