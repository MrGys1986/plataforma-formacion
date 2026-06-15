<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use App\Models\Concerns\VisibleToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiplomaProgram extends Model
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

    public function certificationPrograms(): BelongsToMany
    {
        return $this->belongsToMany(CertificationProgram::class, 'certification_diploma')
            ->withPivot(['order_number', 'is_required'])
            ->withTimestamps();
    }

    public function trainingPrograms(): BelongsToMany
    {
        return $this->belongsToMany(TrainingProgram::class, 'diploma_training_program')
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
            'total_hours' => 'decimal:2',
        ];
    }
}
