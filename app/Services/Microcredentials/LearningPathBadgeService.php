<?php

namespace App\Services\Microcredentials;

use App\Models\Activity;
use App\Models\CertificationProgram;
use App\Models\DiplomaProgram;
use App\Models\LearningPath;
use App\Models\Microcredential;
use App\Models\User;
use App\Models\UserLearningPath;
use Illuminate\Database\Eloquent\Model;

class LearningPathBadgeService
{
    public function __construct(
        private readonly MicrocredentialPayloadService $payloadService,
    ) {}

    public function issueIfCompleted(UserLearningPath $assignment): ?Microcredential
    {
        if ($assignment->status !== 'completada' || (float) $assignment->progress_percentage < 100) {
            return null;
        }

        $assignment->loadMissing(['user', 'learningPath']);
        $path = $assignment->learningPath;

        if (! $path || ! $path->generates_microcredential) {
            return null;
        }

        return $this->issue($assignment->user, $path, $assignment->completed_at);
    }

    public function issue(User $user, Model $source, ?\DateTimeInterface $completedAt = null): ?Microcredential
    {
        if (! $source->generates_microcredential) {
            return null;
        }

        $sourceColumn = match (true) {
            $source instanceof LearningPath => 'learning_path_id',
            $source instanceof DiplomaProgram => 'diploma_program_id',
            $source instanceof CertificationProgram => 'certification_program_id',
            $source instanceof Activity => 'activity_id',
            default => null,
        };

        if ($sourceColumn === null) {
            return null;
        }

        $badge = Microcredential::query()->firstOrCreate(
            [
                'user_id' => $user->id,
                $sourceColumn => $source->id,
            ],
            [
                'name' => 'Insignia de '.$source->name,
                'description' => 'Reconoce la finalización de '.$source->name.'.',
                'skills' => $source->skills ?? null,
                'competencies' => $source instanceof Activity
                    ? ($source->competencies ?: $source->trainingProgram?->competencies)
                    : ($source->competencies ?? null),
                'status' => 'validada',
                'issued_at' => $completedAt ?? now(),
            ],
        );

        if ($badge->wasRecentlyCreated || empty($badge->json_payload)) {
            $badge->load(['user', 'learningPath', 'diplomaProgram', 'certificationProgram', 'activity']);
            $badge->update(['json_payload' => $this->payloadService->buildPayload($badge)]);
        }

        return $badge->refresh();
    }
}
