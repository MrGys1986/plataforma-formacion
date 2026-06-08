<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;
use App\Services\Audit\AuditService;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SecureFileController extends Controller
{
    public function __invoke(FileUpload $fileUpload, AuditService $audit): StreamedResponse
    {
        $this->authorize('download', $fileUpload);

        abort_unless(array_key_exists($fileUpload->disk, config('filesystems.disks')), 404);

        $disk = Storage::disk($fileUpload->disk);

        abort_unless($disk->exists($fileUpload->path), 404);

        $audit->log('archivos', 'descarga', $fileUpload, newValues: [
            'mime_type' => $fileUpload->mime_type,
            'size' => $fileUpload->size,
        ]);

        return $disk->download($fileUpload->path, $fileUpload->original_name);
    }
}
