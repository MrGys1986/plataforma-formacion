<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Evidences\StoreEvidenceRequest;
use App\Models\Enrollment;
use App\Models\Evidence;
use App\Models\FileUpload;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class EvidenceController extends Controller
{
    public function store(StoreEvidenceRequest $request, Enrollment $enrollment): RedirectResponse
    {
        abort_unless($enrollment->user_id === $request->user()->id, 403);
        abort_unless($enrollment->status === 'aprobada', 422, 'La inscripción debe estar aprobada para subir evidencias.');

        $uploadedFile = $request->file('file');
        $storedName = Str::uuid().'.'.$uploadedFile->getClientOriginalExtension();
        $path = $uploadedFile->storeAs(
            'evidences/'.$request->user()->public_id,
            $storedName,
            'local',
        );

        abort_if($path === false, 500, 'No fue posible guardar el archivo.');

        try {
            DB::transaction(function () use ($request, $enrollment, $uploadedFile, $storedName, $path): void {
                $file = FileUpload::create([
                    'original_name' => $uploadedFile->getClientOriginalName(),
                    'stored_name' => $storedName,
                    'disk' => 'local',
                    'path' => $path,
                    'mime_type' => $uploadedFile->getMimeType(),
                    'extension' => $uploadedFile->getClientOriginalExtension(),
                    'size' => $uploadedFile->getSize(),
                    'hash' => hash_file('sha256', $uploadedFile->getRealPath()),
                    'uploaded_by' => $request->user()->id,
                ]);

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
            Storage::disk('local')->delete($path);

            throw $exception;
        }

        return back()->with('status', 'La evidencia fue cargada correctamente.');
    }
}
