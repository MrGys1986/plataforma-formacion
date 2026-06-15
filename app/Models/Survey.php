<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use App\Models\Concerns\VisibleToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Survey extends Model
{
    use HasPublicId, VisibleToUser;

    protected $guarded = [];

    public function questions(): HasMany
    {
        return $this->hasMany(SurveyQuestion::class)->orderBy('order_number');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function trainingPrograms(): BelongsToMany
    {
        return $this->belongsToMany(TrainingProgram::class)->withTimestamps();
    }

    public function diplomaPrograms(): BelongsToMany
    {
        return $this->belongsToMany(DiplomaProgram::class)->withTimestamps();
    }

    protected function casts(): array
    {
        return [
            'is_general' => 'boolean',
        ];
    }
}
