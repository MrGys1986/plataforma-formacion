<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;
use App\Services\Audit\AuditService;
use App\Services\Files\CloudinaryFileStorage;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class SecureFileController extends Controller
{
    public function __invoke(FileUpload $fileUpload, AuditService $audit, CloudinaryFileStorage $cloudinary): Response
    {
        $this->authorize('download', $fileUpload);

        $audit->log('archivos', 'descarga', $fileUpload, newValues: [
            'mime_type' => $fileUpload->mime_type,
            'size' => $fileUpload->size,
        ]);

        if ($fileUpload->disk === 'cloudinary') {
            abort_unless($cloudinary->configured(), 503, 'Cloudinary no está disponible.');

            $url = $cloudinary->temporaryDownloadUrl(
                $fileUpload->path,
                $fileUpload->extension ?? 'bin',
                $fileUpload->original_name,
                config('security.signed_url_minutes', 10),
            );

            return redirect()->away($url);
        }

        abort_unless(array_key_exists($fileUpload->disk, config('filesystems.disks')), 404);

        $disk = Storage::disk($fileUpload->disk);
        abort_unless($disk->exists($fileUpload->path), 404);

        return $disk->download($fileUpload->path, $fileUpload->original_name);
    }
}
