<?php

namespace App\Models;

use App\Services\LearningPaths\LearningPathProgressService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLearningPath extends Model
{
    protected $guarded = [];

    protected static function booted(): void
    {
        static::created(function (UserLearningPath $assignment): void {
            app(LearningPathProgressService::class)->synchronizeAssignment($assignment);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function learningPath(): BelongsTo
    {
        return $this->belongsTo(LearningPath::class);
    }

    protected function casts(): array
    {
        return [
            'progress_percentage' => 'decimal:2',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }
}
