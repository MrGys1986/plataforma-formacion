<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Participant\UploadCourseCertificateRequest;
use App\Http\Requests\Participant\UpdateTeachingEnrollmentRequest;
use App\Http\Requests\Participant\ReviewTeachingEnrollmentRequest;
use App\Models\Activity;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\Evidence;
use App\Models\EvidenceReview;
use App\Services\Files\ManagedFileService;
use App\Http\Requests\Evidences\ReviewEvidenceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class TeachingCourseController extends Controller
{
    public function index(Request $request): View
    {
        $activities = $request->user()
            ->instructedActivities()
            ->with(['trainingProgram', 'activityType', 'area'])
            ->withCount(['enrollments', 'evidences'])
            ->latest('start_date')
            ->paginate(12);

        return view('participant.teaching.index', compact('activities'));
    }

    public function show(Request $request, Activity $activity): View
    {
        abort_unless($activity->instructor_id === $request->user()->id, 403);

        $activity->load(['activityType', 'area']);

        $search = trim((string) $request->query('q'));
        $allEnrollments = $activity->enrollments()
            ->when($search !== '', fn ($query) => $query->whereHas('user', fn ($users) => $users
                ->where('name', 'like', '%'.$search.'%')
                ->orWhere('email', 'like', '%'.$search.'%')))
            ->with([
                'user',
                'certificates.fileUpload',
                'evidences' => fn ($query) => $query->with('fileUpload')->latest(),
            ])
            ->orderByDesc('created_at')
            ->get();

        $pendingEnrollments = $allEnrollments
            ->where('status', 'solicitada')
            ->filter(fn (Enrollment $enrollment): bool => ! $activity->requires_payment
                || in_array($enrollment->payment_status, ['validado', 'pagado'], true))
            ->values();
        $enrollments = $allEnrollments->where('status', 'aprobada')->values();

        return view('participant.teaching.show', compact('activity', 'enrollments', 'pendingEnrollments', 'search'));
    }

    public function reviewEnrollment(
        ReviewTeachingEnrollmentRequest $request,
        Activity $activity,
        Enrollment $enrollment,
    ): RedirectResponse {
        abort_unless($activity->instructor_id === $request->user()->id, 403);
        abort_unless($enrollment->activity_id === $activity->id, 404);
        abort_unless($enrollment->status === 'solicitada', 422, 'Esta solicitud ya fue atendida.');

        $status = $request->validated('status');
        $enrollment->update([
            'status' => $status,
            'approved_by' => $status === 'aprobada' ? $request->user()->id : null,
            'approved_at' => $status === 'aprobada' ? now() : null,
            'rejection_reason' => $status === 'rechazada' ? $request->validated('reason') : null,
        ]);

        return back()->with('status', $status === 'aprobada'
            ? 'La inscripción fue aprobada.'
            : 'La inscripción fue rechazada.');
    }

    public function updateEnrollment(
        UpdateTeachingEnrollmentRequest $request,
        Activity $activity,
        Enrollment $enrollment,
    ): RedirectResponse {
        abort_unless($activity->instructor_id === $request->user()->id, 403);
        abort_unless($enrollment->activity_id === $activity->id, 404);
        abort_unless($enrollment->status === 'aprobada', 422, 'La inscripción debe estar aprobada para evaluar al participante.');

        $completionStatus = $request->validated('completion_status');
        $enrollment->update([
            'final_score' => $request->validated('final_score'),
            'completion_status' => $completionStatus,
            'completed_at' => $completionStatus === 'completado' ? ($enrollment->completed_at ?? now()) : null,
        ]);

        return back()->with('status', 'La evaluación del participante fue actualizada.');
    }

    public function reviewEvidence(
        ReviewEvidenceRequest $request,
        Activity $activity,
        Evidence $evidence,
    ): RedirectResponse {
        abort_unless($activity->instructor_id === $request->user()->id, 403);
        abort_unless($evidence->activity_id === $activity->id, 404);

        DB::transaction(function () use ($request, $evidence): void {
            $previousStatus = $evidence->status;
            $newStatus = $request->validated('status');
            $reason = $request->validated('reason');

            $evidence->update([
                'status' => $newStatus,
                'validated_by' => $newStatus === 'validada' ? $request->user()->id : null,
                'validated_at' => $newStatus === 'validada' ? now() : null,
                'rejection_reason' => $newStatus === 'rechazada' ? $reason : null,
            ]);

            EvidenceReview::create([
                'evidence_id' => $evidence->id,
                'reviewed_by' => $request->user()->id,
                'previous_status' => $previousStatus,
                'new_status' => $newStatus,
                'observations' => $reason,
                'reviewed_at' => now(),
            ]);
        });

        return back()->with('status', $request->validated('status') === 'validada'
            ? 'La evidencia fue validada.'
            : 'La evidencia fue rechazada.');
    }

    public function uploadCertificate(
        UploadCourseCertificateRequest $request,
        Activity $activity,
        Enrollment $enrollment,
        ManagedFileService $files,
    ): RedirectResponse {
        abort_unless($activity->instructor_id === $request->user()->id, 403);
        abort_unless($enrollment->activity_id === $activity->id, 404);
        abort_unless($enrollment->completion_status === 'completado', 422, 'El participante debe completar el curso antes de recibir su constancia.');

        $existing = Certificate::query()->where('enrollment_id', $enrollment->id)->first();
        $previousFile = $existing?->fileUpload;
        $file = $files->store(
            $request->file('certificate'),
            'certificates/'.$activity->public_id.'/'.$enrollment->user?->public_id,
            $request->user()->id,
        );

        try {
            DB::transaction(function () use ($request, $activity, $enrollment, $existing, $file): void {
                $certificate = $existing ?? new Certificate([
                    'folio' => 'CONST-'.now()->format('Y').'-'.Str::upper(Str::random(12)),
                ]);

                $certificate->fill([
                    'user_id' => $enrollment->user_id,
                    'activity_id' => $activity->id,
                    'enrollment_id' => $enrollment->id,
                    'file_upload_id' => $file->id,
                    'certificate_type' => 'terminacion',
                    'issued_by' => $request->user()->id,
                    'issued_at' => now(),
                    'status' => 'emitida',
                ])->save();
            });
        } catch (Throwable $exception) {
            $files->purge($file);
            throw $exception;
        }

        if ($previousFile) {
            $files->scheduleDeletion($previousFile);
        }

        return back()->with('status', $existing
            ? 'La constancia fue reemplazada correctamente.'
            : 'La constancia fue emitida correctamente.');
    }
}
