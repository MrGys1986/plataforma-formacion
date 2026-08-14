<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rubric extends Model
{
    protected $guarded = [];
    public function activity(): BelongsTo { return $this->belongsTo(Activity::class); }
    public function createdBy(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function criteria(): HasMany { return $this->hasMany(RubricCriterion::class)->orderBy('sort_order'); }
    protected function casts(): array { return ['passing_score' => 'decimal:2']; }
}
