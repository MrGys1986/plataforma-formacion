<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RubricCriterion extends Model
{
    protected $guarded = [];
    public function rubric(): BelongsTo { return $this->belongsTo(Rubric::class); }
    protected function casts(): array { return ['weight' => 'decimal:2', 'max_points' => 'decimal:2']; }
}
