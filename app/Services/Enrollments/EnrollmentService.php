<?php

namespace App\Services\Enrollments;

use App\Models\Activity;
use App\Models\Enrollment;
use App\Models\User;
use App\Services\Audit\AuditService;
use Illuminate\Support\Facades\Gate;

class EnrollmentService
{
    public function __construct(private readonly AuditService $audit) {}

    public function requestEnrollment(User $user, Activity $activity): Enrollment
    {
        Gate::forUser($user)->authorize('create', Enrollment::class);

        $enrollment = Enrollment::firstOrCreate(
            ['user_id' => $user->id, 'activity_id' => $activity->id],
            ['status' => 'solicitada', 'requested_at' => now()],
        );
        $this->audit->log('inscripciones', 'solicitud', $enrollment);

        return $enrollment;
    }

    public function approve(Enrollment $enrollment, User $approver): Enrollment
    {
        Gate::forUser($approver)->authorize('approve', $enrollment);
        $enrollment->update([
            'status' => 'aprobada',
            'approved_by' => $approver->id,
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);
        $this->audit->log('inscripciones', 'aprobacion', $enrollment, newValues: ['status' => 'aprobada']);

        return $enrollment->refresh();
    }

    public function reject(Enrollment $enrollment, string $reason): Enrollment
    {
        Gate::authorize('reject', $enrollment);
        $enrollment->update([
            'status' => 'rechazada',
            'rejection_reason' => $reason,
        ]);
        $this->audit->log('inscripciones', 'rechazo', $enrollment, newValues: ['status' => 'rechazada']);

        return $enrollment->refresh();
    }
}
