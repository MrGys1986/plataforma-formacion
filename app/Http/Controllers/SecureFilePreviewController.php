<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;
use App\Services\Audit\AuditService;
use App\Services\Files\CloudinaryFileStorage;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class SecureFilePreviewController extends Controller
{
    public function __invoke(FileUpload $fileUpload, AuditService $audit, CloudinaryFileStorage $cloudinary): Response
    {
        $this->authorize('view', $fileUpload);
        abort_unless(in_array($fileUpload->mime_type, ['application/pdf', 'image/png', 'image/jpeg'], true), 415, 'Este tipo de archivo no admite vista previa.');

        $audit->log('archivos', 'vista_previa', $fileUpload, newValues: ['mime_type' => $fileUpload->mime_type]);

        if ($fileUpload->disk === 'cloudinary') {
            abort_unless($cloudinary->configured(), 503, 'Cloudinary no está disponible.');

            return redirect()->away($cloudinary->temporaryPreviewUrl(
                $fileUpload->path,
                $fileUpload->extension ?? 'bin',
                config('security.signed_url_minutes', 10),
            ));
        }

        abort_unless(array_key_exists($fileUpload->disk, config('filesystems.disks')), 404);
        $disk = Storage::disk($fileUpload->disk);
        abort_unless($disk->exists($fileUpload->path), 404);

        return response()->file($disk->path($fileUpload->path), [
            'Content-Type' => $fileUpload->mime_type,
            'Content-Disposition' => 'inline; filename="'.str_replace('"', '', $fileUpload->original_name).'"',
        ]);
    }
}
