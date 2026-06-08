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
            'recipient' => [
                'public_id' => $microcredential->user?->public_id,
                'name' => $microcredential->user?->name,
            ],
        ];
    }
}
