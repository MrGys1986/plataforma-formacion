<?php

namespace App\Models\Concerns;

use App\Models\User;
use App\Services\Security\RecordVisibility;
use Illuminate\Database\Eloquent\Builder;

trait VisibleToUser
{
    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        return app(RecordVisibility::class)->apply($query, $user);
    }
}
