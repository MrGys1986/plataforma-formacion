<?php

namespace App\Services\Evaluations;

use App\Models\Evaluation;
use App\Models\EvaluationResult;
use App\Models\User;

class EvaluationService
{
    public function registerResult(Evaluation $evaluation, User $user, array $data): EvaluationResult
    {
        return EvaluationResult::updateOrCreate(
            ['evaluation_id' => $evaluation->id, 'user_id' => $user->id],
            $data,
        );
    }
}
