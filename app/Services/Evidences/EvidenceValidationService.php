<?php

namespace App\Services\Evidences;

use App\Models\Evidence;
use App\Models\User;
use App\Services\Audit\AuditService;
use Illuminate\Support\Facades\Gate;

class EvidenceValidationService
{
    public function __construct(private readonly AuditService $audit) {}

    public function validateEvidence(Evidence $evidence, User $validator): Evidence
    {
        Gate::forUser($validator)->authorize('review', $evidence);
        $evidence->update([
            'status' => 'validada',
            'validated_by' => $validator->id,
            'validated_at' => now(),
            'rejection_reason' => null,
        ]);
        $this->audit->log('evidencias', 'validacion', $evidence, newValues: ['status' => 'validada']);

        return $evidence->refresh();
    }

    public function rejectEvidence(Evidence $evidence, User $validator, string $reason): Evidence
    {
        Gate::forUser($validator)->authorize('review', $evidence);
        $evidence->update([
            'status' => 'rechazada',
            'validated_by' => $validator->id,
            'validated_at' => now(),
            'rejection_reason' => $reason,
        ]);
        $this->audit->log('evidencias', 'rechazo', $evidence, newValues: ['status' => 'rechazada']);

        return $evidence->refresh();
    }
}
