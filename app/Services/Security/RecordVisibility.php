<?php

namespace App\Services\Security;

use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Area;
use App\Models\AuditLog;
use App\Models\Certificate;
use App\Models\CertificationProgram;
use App\Models\Competency;
use App\Models\DigitalResource;
use App\Models\DiplomaProgram;
use App\Models\Enrollment;
use App\Models\Evaluation;
use App\Models\Evidence;
use App\Models\FileUpload;
use App\Models\LearningPath;
use App\Models\Microcredential;
use App\Models\Payment;
use App\Models\Survey;
use App\Models\TrainingProgram;
use App\Models\User;
use App\Models\Webinar;
use Illuminate\Database\Eloquent\Builder;

class RecordVisibility
{
    public function apply(Builder $query, User $user): Builder
    {
        if ($user->hasRole('Superadministrador')) {
            return $query;
        }

        return match (true) {
            $query->getModel() instanceof User => $this->users($query, $user),
            $query->getModel() instanceof Area => $this->areas($query, $user),
            $query->getModel() instanceof ActivityType => $query,
            $query->getModel() instanceof Activity => $this->activities($query, $user),
            $query->getModel() instanceof TrainingProgram => $this->trainingPrograms($query, $user),
            $query->getModel() instanceof Competency => $this->learningHierarchy($query, $user),
            $query->getModel() instanceof CertificationProgram => $this->learningHierarchy($query, $user),
            $query->getModel() instanceof DiplomaProgram => $this->learningHierarchy($query, $user),
            $query->getModel() instanceof LearningPath => $this->learningPaths($query, $user),
            $query->getModel() instanceof Enrollment => $this->enrollments($query, $user),
            $query->getModel() instanceof Evidence => $this->evidences($query, $user),
            $query->getModel() instanceof FileUpload => $this->files($query, $user),
            $query->getModel() instanceof Certificate => $this->certificates($query, $user),
            $query->getModel() instanceof Microcredential => $this->microcredentials($query, $user),
            $query->getModel() instanceof Evaluation => $this->evaluations($query, $user),
            $query->getModel() instanceof Survey => $this->surveys($query, $user),
            $query->getModel() instanceof Webinar => $this->webinars($query, $user),
            $query->getModel() instanceof DigitalResource => $this->digitalResources($query, $user),
            $query->getModel() instanceof Payment => $this->payments($query, $user),
            $query->getModel() instanceof AuditLog => $this->auditLogs($query, $user),
            default => $query->whereRaw('1 = 0'),
        };
    }

    private function areas(Builder $query, User $user): Builder
    {
        return $user->hasAnyRole(['Recursos Humanos', 'Calidad Academica', 'Educacion Continua'])
            ? $query
            : $query->whereKey($user->area_id);
    }

    private function users(Builder $query, User $user): Builder
    {
        return match (true) {
            $user->hasRole('Recursos Humanos') => $query
                ->where(fn (Builder $builder) => $builder
                    ->whereNull('user_type')
                    ->orWhere('user_type', '!=', 'externo')),
            $user->hasRole('Educacion Continua') => $query
                ->where(fn (Builder $builder) => $builder
                    ->where('user_type', 'externo')
                    ->orWhereNotNull('external_institution')),
            $user->hasRole('Responsable Area') => $query->where('area_id', $user->area_id),
            default => $query->whereKey($user->id),
        };
    }

    private function activities(Builder $query, User $user): Builder
    {
        return match (true) {
            $user->hasRole('Recursos Humanos') => $query->where('is_external', false),
            $user->hasRole('Educacion Continua') => $query->where('is_external', true),
            $user->hasRole('Profesor') => $query->where(function (Builder $builder) use ($user): void {
                $builder
                    ->where('instructor_id', $user->id)
                    ->orWhereIn('status', ['publicado', 'en_inscripcion', 'cupo_lleno', 'en_curso']);
            }),
            $user->hasRole('Personal') => $query->where('instructor_id', $user->id),
            $user->hasRole('Responsable Area') => $query->where('area_id', $user->area_id),
            $user->hasRole('Calidad Academica') => $query,
            $user->hasRole('Evaluador') => $query->where(function (Builder $builder) use ($user): void {
                $builder
                    ->whereHas('evidences', fn (Builder $evidences) => $evidences->where('assigned_evaluator_id', $user->id))
                    ->orWhereHas('evaluations.results', fn (Builder $results) => $results->where('evaluator_id', $user->id));
            }),
            default => $query->where('status', 'publicado'),
        };
    }

    private function trainingPrograms(Builder $query, User $user): Builder
    {
        return match (true) {
            $user->hasRole('Recursos Humanos') => $query->where('is_external', false),
            $user->hasRole('Educacion Continua') => $query->where('is_external', true),
            $user->hasRole('Responsable Area') => $query->where('area_id', $user->area_id),
            $user->hasRole('Calidad Academica') => $query,
            default => $query->where('status', 'activo'),
        };
    }

    private function learningHierarchy(Builder $query, User $user): Builder
    {
        return match (true) {
            $user->hasAnyRole(['Recursos Humanos', 'Calidad Academica']) => $query,
            $user->hasRole('Responsable Area') => $query->where('area_id', $user->area_id),
            default => $query->where('status', 'activo'),
        };
    }

    private function learningPaths(Builder $query, User $user): Builder
    {
        return match (true) {
            $user->hasAnyRole(['Recursos Humanos', 'Calidad Academica']) => $query,
            $user->hasRole('Responsable Area') => $query->where('area_id', $user->area_id),
            default => $query->where('status', 'publicada'),
        };
    }

    private function enrollments(Builder $query, User $user): Builder
    {
        return match (true) {
            $user->hasRole('Recursos Humanos') => $query->whereHas('activity', fn (Builder $activity) => $activity->where('is_external', false)),
            $user->hasRole('Educacion Continua') => $query->whereHas('activity', fn (Builder $activity) => $activity->where('is_external', true)),
            $user->hasRole('Profesor') => $query->where(function (Builder $builder) use ($user): void {
                $builder
                    ->where('user_id', $user->id)
                    ->orWhereHas('activity', fn (Builder $activity) => $activity->where('instructor_id', $user->id));
            }),
            $user->hasRole('Personal') => $query->whereHas('activity', fn (Builder $activity) => $activity->where('instructor_id', $user->id)),
            $user->hasRole('Responsable Area') => $query->whereHas('activity', fn (Builder $activity) => $activity->where('area_id', $user->area_id)),
            default => $query->where('user_id', $user->id),
        };
    }

    private function evidences(Builder $query, User $user): Builder
    {
        return match (true) {
            $user->hasRole('Calidad Academica') => $query,
            $user->hasRole('Recursos Humanos') => $query->whereHas('activity', fn (Builder $activity) => $activity->where('is_external', false)),
            $user->hasRole('Educacion Continua') => $query->whereHas('activity', fn (Builder $activity) => $activity->where('is_external', true)),
            $user->hasRole('Profesor') => $query->where(function (Builder $builder) use ($user): void {
                $builder
                    ->where('user_id', $user->id)
                    ->orWhereHas('activity', fn (Builder $activity) => $activity->where('instructor_id', $user->id));
            }),
            $user->hasRole('Personal') => $query->whereHas('activity', fn (Builder $activity) => $activity->where('instructor_id', $user->id)),
            $user->hasRole('Evaluador') => $query->where('assigned_evaluator_id', $user->id),
            $user->hasRole('Responsable Area') => $query->whereHas('activity', fn (Builder $activity) => $activity->where('area_id', $user->area_id)),
            default => $query->where('user_id', $user->id),
        };
    }

    private function files(Builder $query, User $user): Builder
    {
        return $query->where(function (Builder $builder) use ($user): void {
            $builder
                ->where('uploaded_by', $user->id)
                ->orWhereHas('evidences', fn (Builder $evidences) => $evidences->visibleTo($user))
                ->orWhereHas('certificates', fn (Builder $certificates) => $certificates->visibleTo($user))
                ->orWhereHas('digitalResources', fn (Builder $resources) => $resources->visibleTo($user))
                ->orWhereHas('paymentProofs', fn (Builder $payments) => $payments->visibleTo($user));
        });
    }

    private function certificates(Builder $query, User $user): Builder
    {
        return match (true) {
            $user->hasRole('Calidad Academica') => $query,
            $user->hasRole('Recursos Humanos') => $query->whereHas('activity', fn (Builder $activity) => $activity->where('is_external', false)),
            $user->hasRole('Educacion Continua') => $query->whereHas('activity', fn (Builder $activity) => $activity->where('is_external', true)),
            $user->hasRole('Profesor') => $query->where(function (Builder $builder) use ($user): void {
                $builder
                    ->where('user_id', $user->id)
                    ->orWhereHas('activity', fn (Builder $activity) => $activity->where('instructor_id', $user->id));
            }),
            $user->hasRole('Personal') => $query->whereHas('activity', fn (Builder $activity) => $activity->where('instructor_id', $user->id)),
            $user->hasRole('Evaluador') => $query->whereHas('user', fn (Builder $owner) => $owner
                ->whereHas('evaluationResults', fn (Builder $results) => $results->where('evaluator_id', $user->id))),
            $user->hasRole('Responsable Area') => $query->whereHas('activity', fn (Builder $activity) => $activity->where('area_id', $user->area_id)),
            default => $query->where('user_id', $user->id),
        };
    }

    private function microcredentials(Builder $query, User $user): Builder
    {
        return match (true) {
            $user->hasRole('Calidad Academica') => $query,
            $user->hasRole('Recursos Humanos') => $query->whereHas('activity', fn (Builder $activity) => $activity->where('is_external', false)),
            $user->hasRole('Educacion Continua') => $query->whereHas('activity', fn (Builder $activity) => $activity->where('is_external', true)),
            default => $query->where('user_id', $user->id),
        };
    }

    private function evaluations(Builder $query, User $user): Builder
    {
        return match (true) {
            $user->hasRole('Calidad Academica') => $query,
            $user->hasRole('Recursos Humanos') => $query->whereHas('activity', fn (Builder $activity) => $activity->where('is_external', false)),
            $user->hasAnyRole(['Profesor', 'Personal']) => $query->whereHas('activity', fn (Builder $activity) => $activity->where('instructor_id', $user->id)),
            $user->hasRole('Evaluador') => $query->whereHas('results', fn (Builder $results) => $results->where('evaluator_id', $user->id)),
            default => $query->whereRaw('1 = 0'),
        };
    }

    private function surveys(Builder $query, User $user): Builder
    {
        return $user->hasAnyRole(['Recursos Humanos', 'Calidad Academica'])
            ? $query
            : $query->where('status', 'activa');
    }

    private function webinars(Builder $query, User $user): Builder
    {
        return match (true) {
            $user->hasRole('Educacion Continua') => $query,
            $user->hasRole('Responsable Area') => $query->where('area_id', $user->area_id),
            default => $query->where('status', 'publicado'),
        };
    }

    private function digitalResources(Builder $query, User $user): Builder
    {
        return match (true) {
            $user->hasRole('Educacion Continua') => $query,
            $user->hasRole('Responsable Area') => $query->where('area_id', $user->area_id),
            default => $query->where('status', 'activo')->where('visibility', 'interno'),
        };
    }

    private function payments(Builder $query, User $user): Builder
    {
        return match (true) {
            $user->hasRole('Educacion Continua') => $query->whereHas('activity', fn (Builder $activity) => $activity->where('is_external', true)),
            default => $query->where('user_id', $user->id),
        };
    }

    private function auditLogs(Builder $query, User $user): Builder
    {
        return $user->hasRole('Calidad Academica')
            ? $query
            : $query->whereRaw('1 = 0');
    }
}
