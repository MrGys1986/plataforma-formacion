<?php

namespace App\Services\Microcredentials;

use App\Models\Microcredential;
use App\Models\UserLearningPath;

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

        if (! $path) {
            return null;
        }

        $badge = Microcredential::query()->firstOrCreate(
            [
                'user_id' => $assignment->user_id,
                'learning_path_id' => $assignment->learning_path_id,
            ],
            [
                'name' => 'Insignia de '.$path->name,
                'description' => 'Reconoce la finalización de la ruta de aprendizaje '.$path->name.'.',
                'skills' => $path->skills,
                'competencies' => $path->competencies,
                'status' => 'validada',
                'issued_at' => $assignment->completed_at ?? now(),
            ],
        );

        if ($badge->wasRecentlyCreated || empty($badge->json_payload)) {
            $badge->load(['user', 'learningPath']);
            $badge->update(['json_payload' => $this->payloadService->buildPayload($badge)]);
        }

        return $badge->refresh();
    }
}
