<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\CertificateTemplate;
use App\Models\CertificationProgram;
use App\Models\Competency;
use App\Models\DigitalResource;
use App\Models\DiplomaProgram;
use App\Models\Enrollment;
use App\Models\Evaluation;
use App\Models\LearningPath;
use App\Models\Survey;
use App\Models\TrainingProgram;
use App\Models\Webinar;
use App\Services\Audit\AuditService;
use App\Services\Learning\LearningProgressService;
use Illuminate\Database\Eloquent\Model;

class AuditableObserver
{
    public function creating(Model $model): void
    {
        if (blank($model->getAttribute('created_by')) && (
            $model instanceof Activity
            || $model instanceof CertificateTemplate
            || $model instanceof CertificationProgram
            || $model instanceof Competency
            || $model instanceof DigitalResource
            || $model instanceof DiplomaProgram
            || $model instanceof Evaluation
            || $model instanceof LearningPath
            || $model instanceof Survey
            || $model instanceof TrainingProgram
            || $model instanceof Webinar
        )) {
            $model->setAttribute('created_by', auth()->id());
        }
    }

    public function created(Model $model): void
    {
        $this->audit($model, 'creacion', [], $model->getAttributes());
        $this->refreshLearningProgress($model);
    }

    public function updated(Model $model): void
    {
        $changes = collect($model->getChanges())->except('updated_at')->all();

        if ($changes === []) {
            return;
        }

        $oldValues = collect($model->getOriginal())
            ->only(array_keys($changes))
            ->all();

        $this->audit($model, 'actualizacion', $oldValues, $changes);
        $this->refreshLearningProgress($model);
    }

    public function deleted(Model $model): void
    {
        $this->audit($model, 'eliminacion_logica', $model->getOriginal(), []);
    }

    public function restored(Model $model): void
    {
        $this->audit($model, 'restauracion', [], $model->getAttributes());
    }

    private function audit(Model $model, string $action, array $oldValues, array $newValues): void
    {
        app(AuditService::class)->log(
            $model->getTable(),
            $action,
            $model,
            $oldValues,
            $newValues,
        );
    }

    private function refreshLearningProgress(Model $model): void
    {
        if (! $model instanceof Enrollment || ! $model->user_id) {
            return;
        }

        $user = $model->user()->first();

        if ($user) {
            app(LearningProgressService::class)->recalculate($user);
        }
    }
}
