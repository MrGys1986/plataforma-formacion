<?php

namespace App\Services\Reports;

use App\Models\Activity;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\Evidence;
use App\Models\Microcredential;
use App\Models\Payment;
use App\Models\SurveyResponse;
use App\Models\TrainingProgram;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;

class InstitutionalReportService
{
    public const OVERVIEW_ROLES = [
        'Superadministrador',
        'Recursos Humanos',
        'Calidad Academica',
        'Educacion Continua',
        'Responsable Area',
    ];

    public function institutionalOverview(User $user): array
    {
        abort_unless($user->hasAnyRole(self::OVERVIEW_ROLES), 403);

        return [
            'metrics' => [
                'Usuarios activos' => $this->overviewQuery(User::class, $user)->where('status', 'activo')->count(),
                'Programas formativos' => $this->overviewQuery(TrainingProgram::class, $user)->count(),
                'Actividades abiertas' => $this->overviewQuery(Activity::class, $user)->whereIn('status', ['en_inscripcion', 'publicado', 'en_curso'])->count(),
                'Inscripciones' => $this->overviewQuery(Enrollment::class, $user)->count(),
                'Formaciones completadas' => $this->overviewQuery(Enrollment::class, $user)->where('completion_status', 'completado')->count(),
                'Evidencias pendientes' => $this->overviewQuery(Evidence::class, $user)->where('status', 'pendiente')->count(),
                'Respuestas de encuestas' => $this->overviewQuery(SurveyResponse::class, $user)->count(),
                'Microcredenciales emitidas' => $this->overviewQuery(Microcredential::class, $user)->where('status', 'validada')->count(),
            ],
            'validatedPayments' => $user->hasAnyRole(['Superadministrador', 'Educacion Continua'])
                && Gate::forUser($user)->allows('viewAny', Payment::class)
                ? $this->overviewQuery(Payment::class, $user)->where('status', 'validado')->sum('amount')
                : null,
            'completionByProgram' => $this->overviewQuery(TrainingProgram::class, $user)
                ->with('activityType')
                ->withCount([
                    'editions' => fn (Builder $query): Builder => $this->scopeOverview($query, $user),
                    'enrollments as completed_enrollments_count' => fn (Builder $query): Builder => $this->scopeOverview($query, $user)
                        ->where('completion_status', 'completado'),
                ])
                ->orderByDesc('completed_enrollments_count')
                ->limit(10)
                ->get(),
        ];
    }

    private function overviewQuery(string $model, User $user): Builder
    {
        return $this->scopeOverview($model::query(), $user);
    }

    private function scopeOverview(Builder $query, User $user): Builder
    {
        // Financial totals follow the existing payment permissions, including for academic roles.
        if ($query->getModel() instanceof Payment) {
            return $query->visibleTo($user);
        }

        if ($user->hasAnyRole(['Superadministrador', 'Calidad Academica'])) {
            return $query;
        }

        $isAreaScope = ! $user->hasAnyRole(['Recursos Humanos', 'Educacion Continua']);

        // A missing area is not permission to consult all unassigned institutional records.
        if ($isAreaScope && $user->area_id === null) {
            return $query->whereRaw('1 = 0');
        }

        if ($query->getModel() instanceof Microcredential) {
            if (! $isAreaScope) {
                return $query->visibleTo($user);
            }

            return $query->where(function (Builder $badges) use ($user): void {
                foreach (['activity', 'learningPath', 'diplomaProgram', 'certificationProgram'] as $source) {
                    $badges->orWhereHas($source, fn (Builder $origin): Builder => $origin->where('area_id', $user->area_id));
                }
            });
        }

        if ($query->getModel() instanceof Enrollment
            || $query->getModel() instanceof Evidence
            || $query->getModel() instanceof SurveyResponse) {
            return $query->whereHas('activity', fn (Builder $activities): Builder => $activities->visibleTo($user));
        }

        return $query->visibleTo($user);
    }

    public function trainingSummary(User $user): array
    {
        $query = Enrollment::query()->visibleTo($user);

        return [
            'total' => (clone $query)->count(),
            'solicitadas' => (clone $query)->where('status', 'solicitada')->count(),
            'aprobadas' => (clone $query)->where('status', 'aprobada')->count(),
            'rechazadas' => (clone $query)->where('status', 'rechazada')->count(),
            'no_iniciadas' => (clone $query)->where('completion_status', 'no_iniciado')->count(),
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
            'rechazadas' => (clone $query)->where('status', 'rechazada')->count(),
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
