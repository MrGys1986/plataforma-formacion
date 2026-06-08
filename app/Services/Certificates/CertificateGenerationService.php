<?php

namespace App\Services\Certificates;

use App\Models\Certificate;
use App\Models\Enrollment;
use App\Services\Audit\AuditService;
use Illuminate\Support\Facades\Gate;

class CertificateGenerationService
{
    public function __construct(private readonly AuditService $audit) {}

    public function canGenerate(Enrollment $enrollment): bool
    {
        return $enrollment->completion_status === 'completado'
            && $enrollment->activity?->generates_certificate === true;
    }

    public function generate(Certificate $certificate): Certificate
    {
        Gate::authorize('update', $certificate);
        // TODO: integrar la plantilla, el PDF y el almacenamiento institucional.
        $certificate->update([
            'status' => 'emitida',
            'issued_at' => $certificate->issued_at ?? now(),
        ]);
        $this->audit->log('constancias', 'generacion', $certificate, newValues: ['status' => 'emitida']);

        return $certificate->refresh();
    }
}
