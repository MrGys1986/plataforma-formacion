<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreActivityCertificateRequest;
use App\Http\Requests\Admin\StoreActivityDocumentRequest;
use App\Http\Requests\Admin\StoreActivityEnrollmentRequest;
use App\Http\Requests\Admin\StoreActivityRequest;
use App\Http\Requests\Admin\StoreTrainingProgramRequest;
use App\Http\Requests\Admin\UpdateActivityClassroomRequest;
use App\Models\Activity;
use App\Models\Certificate;
use App\Models\DigitalResource;
use App\Models\Enrollment;
use App\Models\TrainingProgram;
use App\Services\Files\ManagedFileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Throwable;

class TrainingWorkflowController extends Controller
{
    public function storeProgram(StoreTrainingProgramRequest $request): RedirectResponse
    {
        Gate::authorize('create', TrainingProgram::class);

        $validated = $request->validated();
        $program = TrainingProgram::create([
            ...$validated,
            'slug' => $this->uniqueSlug(TrainingProgram::class, $validated['name']),
            'created_by' => $request->user()->id,
            'default_cost' => $validated['default_cost'] ?? 0,
            'requires_approval' => $request->boolean('requires_approval'),
            'requires_payment' => $request->boolean('requires_payment'),
            'requires_evaluation' => $request->boolean('requires_evaluation'),
            'requires_survey' => $request->boolean('requires_survey'),
            'generates_certificate' => $request->boolean('generates_certificate'),
        ]);

        return redirect()->route('dashboard', [
            'section' => 'programas',
            'program' => $program->public_id,
            'action' => 'create-activity',
        ])->with('status', 'Programa creado. Ahora registra su primera actividad o edición.');
    }

    public function storeActivity(StoreActivityRequest $request): RedirectResponse
    {
        Gate::authorize('create', Activity::class);

        $validated = $request->validated();
        $program = TrainingProgram::query()->findOrFail($validated['training_program_id']);

        $activity = Activity::create([
            ...$validated,
            'name' => filled($validated['name'] ?? null) ? $validated['name'] : null,
            'created_by' => $request->user()->id,
            'area_id' => $program->area_id,
            'activity_type_id' => $program->activity_type_id,
        ]);

        return redirect()->route('dashboard', [
            'section' => 'programas',
            'program' => $program->public_id,
            'activity' => $activity->public_id,
        ])->with('status', 'Actividad creada. Continúa con Classroom, documentos, participantes y constancias.');
    }

    public function updateClassroom(
        UpdateActivityClassroomRequest $request,
        Activity $activity,
    ): RedirectResponse {
        Gate::authorize('update', $activity);
        $activity->update($request->validated());

        return $this->activityRedirect($activity, 'El enlace de Classroom se actualizó correctamente.');
    }

    public function storeEnrollment(
        StoreActivityEnrollmentRequest $request,
        Activity $activity,
    ): RedirectResponse {
        $validated = $request->validated();
        $approved = $validated['status'] === 'aprobada';

        Enrollment::create([
            'activity_id' => $activity->id,
            'user_id' => $validated['user_id'],
            'status' => $validated['status'],
            'requested_at' => now(),
            'approved_by' => $approved ? $request->user()->id : null,
            'approved_at' => $approved ? now() : null,
            'payment_status' => $activity->requires_payment ? 'pendiente' : 'no_aplica',
            'completion_status' => 'no_iniciado',
        ]);

        return $this->activityRedirect($activity, 'Participante inscrito en esta actividad.');
    }

    public function storeDocument(
        StoreActivityDocumentRequest $request,
        Activity $activity,
        ManagedFileService $files,
    ): RedirectResponse {
        Gate::authorize('update', $activity);

        $file = $files->store(
            $request->file('document'),
            'activities/'.$activity->public_id.'/documents',
            $request->user()->id,
        );

        try {
            DigitalResource::create([
                'activity_id' => $activity->id,
                'area_id' => $activity->area_id,
                'file_upload_id' => $file->id,
                'created_by' => $request->user()->id,
                'title' => $request->validated('title'),
                'resource_type' => $request->validated('document_type'),
                'visibility' => 'interno',
                'status' => 'activo',
            ]);
        } catch (Throwable $exception) {
            $files->purge($file);
            throw $exception;
        }

        return $this->activityRedirect($activity, 'Documento incorporado al expediente de la actividad.');
    }

    public function storeCertificate(
        StoreActivityCertificateRequest $request,
        Activity $activity,
        ManagedFileService $files,
    ): RedirectResponse {
        Gate::authorize('update', $activity);
        abort_unless($activity->generates_certificate, 422, 'Esta actividad no está configurada para generar constancias.');

        $enrollment = Enrollment::query()
            ->with('user')
            ->whereKey($request->integer('enrollment_id'))
            ->where('activity_id', $activity->id)
            ->firstOrFail();

        abort_unless($enrollment->completion_status === 'completado', 422, 'El participante debe completar la actividad antes de recibir su constancia.');
        abort_unless($enrollment->status === 'aprobada', 422, 'La inscripción debe estar aprobada para recibir su constancia.');

        $existing = Certificate::query()
            ->with('fileUpload')
            ->where('enrollment_id', $enrollment->id)
            ->first();
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

        return $this->activityRedirect(
            $activity,
            $existing ? 'La constancia fue reemplazada.' : 'La constancia fue emitida correctamente.',
        );
    }

    private function activityRedirect(Activity $activity, string $message): RedirectResponse
    {
        $activity->loadMissing('trainingProgram');

        return redirect(route('dashboard', [
            'section' => $activity->trainingProgram ? 'programas' : 'actividades',
            'program' => $activity->trainingProgram?->public_id,
            'activity' => $activity->public_id,
        ]).'#activity-workflow')->with('status', $message);
    }

    /** @param class-string<TrainingProgram> $model */
    private function uniqueSlug(string $model, string $name): string
    {
        $base = Str::slug($name) ?: 'programa';
        $slug = $base;
        $suffix = 2;

        while ($model::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
