<?php
namespace Database\Seeders;
use App\Models\Certificate;
use App\Models\EvaluationResult;
use Illuminate\Database\Seeder;

class EvaluatorCertificationDemoSeeder extends Seeder
{
    public function run(): void
    {
        $result = EvaluationResult::query()
            ->where('result', 'aprobado')
            ->whereNotNull('evaluator_id')
            ->with(['evaluation.activity', 'enrollment'])
            ->first();
        if (! $result || ! $result->evaluation?->activity || ! $result->enrollment) { return; }

        $result->enrollment->update([
            'completion_status' => 'completado',
            'final_score' => $result->score,
            'completed_at' => $result->evaluated_at ?? now(),
        ]);

        $folio = 'CERT-AREA-'.str_pad((string) $result->user_id, 4, '0', STR_PAD_LEFT);
        Certificate::query()->updateOrCreate(
            ['folio' => $folio],
            [
                'user_id' => $result->user_id,
                'activity_id' => $result->evaluation->activity_id,
                'enrollment_id' => $result->enrollment_id,
                'certificate_type' => 'terminacion',
                'verification_url' => route('public.certificates.verify', $folio),
                'issued_by' => $result->evaluator_id,
                'issued_at' => now()->subDay(),
                'status' => 'disponible',
            ],
        );
    }
}
