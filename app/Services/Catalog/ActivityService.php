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
        $activity->update(['status' => 'publicado']);
        $this->audit->log('ediciones', 'publicacion', $activity, ['status' => $oldStatus], ['status' => 'publicado']);

        return $activity->refresh();
    }

    public function archive(Activity $activity): Activity
    {
        Gate::authorize('update', $activity);
        $oldStatus = $activity->status;
        $activity->update(['status' => 'archivado']);
        $this->audit->log('ediciones', 'archivo', $activity, ['status' => $oldStatus], ['status' => 'archivado']);

        return $activity->refresh();
    }
}
