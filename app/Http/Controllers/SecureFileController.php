<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;
use App\Services\Audit\AuditService;
use App\Services\Files\CloudinaryFileStorage;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
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
                config('security.signed_url_minutes', 10),
                $fileUpload->resource_type ?? 'raw',
                $fileUpload->delivery_type ?? 'private',
            );

            try {
                $remote = Http::connectTimeout(5)->timeout(15)->get($url);
            } catch (ConnectionException $exception) {
                report($exception);
                abort(503, 'No fue posible descargar el archivo de Cloudinary. Vuelve a intentarlo.');
            }

            abort_unless($remote->successful(), 502, 'Cloudinary no pudo entregar el archivo.');
            $stream = $remote->toPsrResponse()->getBody();

            // Keep the institution's filename instead of Cloudinary's temporary upload filename.
            return response()->streamDownload(function () use ($stream): void {
                try {
                    while (! $stream->eof()) {
                        echo $stream->read(65536);
                    }
                } finally {
                    $stream->close();
                }
            }, $fileUpload->downloadName(), [
                'Content-Type' => $fileUpload->mime_type ?: 'application/octet-stream',
                'Cache-Control' => 'private, no-store',
            ]);
        }

        abort_unless(array_key_exists($fileUpload->disk, config('filesystems.disks')), 404);

        $disk = Storage::disk($fileUpload->disk);
        abort_unless($disk->exists($fileUpload->path), 404);

        return $disk->download($fileUpload->path, $fileUpload->downloadName());
    }
}
