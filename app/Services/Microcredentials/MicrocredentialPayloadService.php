<?php

namespace App\Services\Microcredentials;

use App\Models\Microcredential;

class MicrocredentialPayloadService
{
    public function buildPayload(Microcredential $microcredential): array
    {
        return [
            'public_id' => $microcredential->public_id,
            'name' => $microcredential->name,
            'description' => $microcredential->description,
            'skills' => $microcredential->skills,
            'competencies' => $microcredential->competencies,
            'issued_at' => $microcredential->issued_at?->toIso8601String(),
            'learning_path' => $microcredential->learningPath ? [
                'public_id' => $microcredential->learningPath->public_id,
                'name' => $microcredential->learningPath->name,
            ] : null,
            'recipient' => [
                'public_id' => $microcredential->user?->public_id,
                'name' => $microcredential->user?->name,
            ],
        ];
    }
}
