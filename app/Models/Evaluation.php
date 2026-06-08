<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use App\Models\Concerns\VisibleToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    use HasPublicId, VisibleToUser;

    protected $guarded = [];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(EvaluationResult::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected function casts(): array
    {
        return [
            'minimum_score' => 'decimal:2',
        ];
    }
}
