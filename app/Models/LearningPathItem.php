<?php

namespace App\Models;

use App\Services\LearningPaths\LearningPathProgressService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningPathItem extends Model
{
    protected $guarded = [];

    protected static function booted(): void
    {
        static::saved(function (LearningPathItem $item): void {
            $item->learningPath
                ->userLearningPaths()
                ->each(fn (UserLearningPath $assignment) => app(LearningPathProgressService::class)
                    ->synchronizeAssignment($assignment));
        });
    }

    public function learningPath(): BelongsTo
    {
        return $this->belongsTo(LearningPath::class);
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
            'minimum_score' => 'decimal:2',
        ];
    }
}
