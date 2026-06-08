<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use App\Models\Concerns\VisibleToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ActivityType extends Model
{
    use HasPublicId, VisibleToUser;

    protected $guarded = [];

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    protected function casts(): array
    {
        return [
            'default_generates_certificate' => 'boolean',
            'default_generates_microcredential' => 'boolean',
        ];
    }
}
