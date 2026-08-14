<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Evidences\StoreEvidenceRequest;
use App\Models\Enrollment;
use App\Models\Evidence;
use App\Services\Files\ManagedFileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class EvidenceController extends Controller
{
    public function store(StoreEvidenceRequest $request, Enrollment $enrollment, ManagedFileService $files): RedirectResponse
    {
        abort_unless($enrollment->user_id === $request->user()->id, 403);
        abort_unless($enrollment->status === 'aprobada', 422, 'La inscripción debe estar aprobada para subir evidencias.');

        $uploadedFile = $request->file('file');
        $file = $files->store(
            $uploadedFile,
            'evidences/'.$request->user()->public_id,
            $request->user()->id,
        );

        try {
            DB::transaction(function () use ($request, $enrollment, $file): void {
                Evidence::create([
                    'user_id' => $request->user()->id,
                    'activity_id' => $enrollment->activity_id,
                    'enrollment_id' => $enrollment->id,
                    'file_upload_id' => $file->id,
                    'evidence_type' => $request->validated('evidence_type'),
                    'title' => $request->validated('title'),
                    'description' => $request->validated('description'),
                    'status' => 'pendiente',
                    'uploaded_by' => $request->user()->id,
                ]);
            });
        } catch (Throwable $exception) {
            $files->purge($file);

            throw $exception;
        }

        return back()->with('status', 'La evidencia fue cargada correctamente.');
    }
}
