<?php

namespace App\Filament\Concerns;

use App\Models\Activity;
use App\Models\TrainingProgram;

trait ResolvesTrainingContext
{
    protected bool $hasResolvedActivityContext = false;

    protected ?Activity $resolvedActivityContext = null;

    protected bool $hasResolvedTrainingProgramContext = false;

    protected ?TrainingProgram $resolvedTrainingProgramContext = null;

    protected function getActivityContext(): ?Activity
    {
        if ($this->hasResolvedActivityContext) {
            return $this->resolvedActivityContext;
        }

        $this->hasResolvedActivityContext = true;

        if (! request()->filled('activity')) {
            return $this->resolvedActivityContext = null;
        }

        return $this->resolvedActivityContext = Activity::query()
            ->visibleTo(auth()->user())
            ->with(['trainingProgram.activityType'])
            ->find(request()->integer('activity'));
    }

    protected function getTrainingProgramContext(): ?TrainingProgram
    {
        if ($this->hasResolvedTrainingProgramContext) {
            return $this->resolvedTrainingProgramContext;
        }

        $this->hasResolvedTrainingProgramContext = true;

        if (! request()->filled('training_program')) {
            return $this->resolvedTrainingProgramContext = null;
        }

        return $this->resolvedTrainingProgramContext = TrainingProgram::query()
            ->visibleTo(auth()->user())
            ->with('activityType')
            ->find(request()->integer('training_program'));
    }
}
