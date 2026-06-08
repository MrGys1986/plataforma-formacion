<?php

namespace App\Services\Reports;

use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\Evidence;
use App\Models\User;

class InstitutionalReportService
{
    public function trainingSummary(User $user): array
    {
        $query = Enrollment::query()->visibleTo($user);

        return [
            'total' => (clone $query)->count(),
            'completadas' => (clone $query)->where('completion_status', 'completado')->count(),
        ];
    }

    public function evidenceSummary(User $user): array
    {
        $query = Evidence::query()->visibleTo($user);

        return [
            'total' => (clone $query)->count(),
            'pendientes' => (clone $query)->where('status', 'pendiente')->count(),
            'validadas' => (clone $query)->where('status', 'validada')->count(),
        ];
    }

    public function certificateSummary(User $user): array
    {
        $query = Certificate::query()->visibleTo($user);

        return [
            'total' => (clone $query)->count(),
            'emitidas' => (clone $query)->where('status', 'emitida')->count(),
        ];
    }
}
