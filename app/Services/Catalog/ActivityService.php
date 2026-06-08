<?php

namespace App\Services\Catalog;

use App\Models\Activity;
use App\Services\Audit\AuditService;
use Illuminate\Support\Facades\Gate;

class ActivityService
{
    public function __construct(private readonly AuditService $audit) {}

    public function publish(Activity $activity): Activity
    {
        Gate::authorize('publish', $activity);
        $oldStatus = $activity->status;
        $activity->update(['status' => 'publicada']);
        $this->audit->log('actividades', 'publicacion', $activity, ['status' => $oldStatus], ['status' => 'publicada']);

        return $activity->refresh();
    }

    public function archive(Activity $activity): Activity
    {
        Gate::authorize('update', $activity);
        $oldStatus = $activity->status;
        $activity->update(['status' => 'archivada']);
        $this->audit->log('actividades', 'archivo', $activity, ['status' => $oldStatus], ['status' => 'archivada']);

        return $activity->refresh();
    }
}
