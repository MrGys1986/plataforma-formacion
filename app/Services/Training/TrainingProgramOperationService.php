<?php

namespace App\Services\Training;

use App\Models\Activity;
use App\Models\TrainingProgram;

class TrainingProgramOperationService
{
    public function resolve(TrainingProgram $program): Activity
    {
        $activity = Activity::query()
            ->where('training_program_id', $program->id)
            ->latest('id')
            ->first();

        if ($activity) {
            return $activity;
        }

        $deletedActivity = Activity::onlyTrashed()
            ->where('training_program_id', $program->id)
            ->latest('id')
            ->first();

        if ($deletedActivity) {
            $deletedActivity->restore();

            return $deletedActivity->refresh();
        }

        return $program->activities()->create([
            'activity_type_id' => $program->activity_type_id,
            'area_id' => $program->area_id,
            'name' => $program->name,
            'slug' => $program->slug.'-operacion-'.$program->id,
            'description' => $program->description,
            'duration_hours' => $program->duration_hours,
            'modality' => $program->default_modality,
            'cost' => $program->default_cost,
            'is_external' => $program->is_external,
            'requires_approval' => $program->requires_approval,
            'requires_payment' => $program->requires_payment,
            'requires_evaluation' => $program->requires_evaluation,
            'requires_survey' => $program->requires_survey,
            'generates_certificate' => $program->generates_certificate,
            'generates_microcredential' => $program->generates_microcredential,
            'status' => 'borrador',
            'created_by' => auth()->id(),
        ]);
    }
}
