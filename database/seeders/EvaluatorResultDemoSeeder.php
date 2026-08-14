<?php
namespace Database\Seeders;
use App\Models\Activity;
use App\Models\Evaluation;
use App\Models\EvaluationResult;
use App\Models\User;
use Illuminate\Database\Seeder;

class EvaluatorResultDemoSeeder extends Seeder
{
    public function run(): void
    {
        $activity = Activity::query()->where('slug', 'capacitacion-institucional-area-demo')->first();
        $evaluator = User::query()->role('Evaluador')->where('area_id', $activity?->area_id)->first();
        if (! $activity || ! $evaluator) { return; }

        $evaluation = Evaluation::query()->updateOrCreate(
            ['activity_id' => $activity->id, 'name' => 'Proyecto de mejora institucional'],
            ['description' => 'Evaluación final basada en la rúbrica de proyecto de mejora.', 'evaluation_type' => 'calificacion', 'minimum_score' => 70, 'status' => 'activa', 'created_by' => $evaluator->id],
        );

        $enrollments = $activity->enrollments()->where('status', 'aprobada')->with('user')->limit(2)->get();
        foreach ($enrollments as $index => $enrollment) {
            EvaluationResult::query()->updateOrCreate(
                ['evaluation_id' => $evaluation->id, 'user_id' => $enrollment->user_id],
                ['enrollment_id' => $enrollment->id, 'evaluator_id' => $evaluator->id, 'score' => $index === 0 ? 88 : 68, 'result' => $index === 0 ? 'aprobado' : 'no_aprobado', 'observations' => $index === 0 ? 'La propuesta es viable y presenta indicadores claros de seguimiento.' : 'Debe fortalecer la fundamentación y precisar los indicadores de impacto.', 'evaluated_at' => now()->subDays(2 - $index)],
            );
        }
    }
}
