<?php

namespace App\Console\Commands;

use App\Models\FileUpload;
use App\Services\Files\CloudinaryFileStorage;
use Illuminate\Console\Command;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Throwable;

class MigrateFilesToCloudinary extends Command
{
    protected $signature = 'files:migrate-to-cloudinary {--execute : Ejecuta la migración; sin esta opción solo informa}';
    protected $description = 'Migra archivos locales registrados a Cloudinary sin perder compatibilidad';

    public function handle(CloudinaryFileStorage $cloudinary): int
    {
        if (! $cloudinary->configured()) { $this->error('Cloudinary no está configurado.'); return self::FAILURE; }
        $records = FileUpload::query()->whereIn('disk', ['local', 'public'])->get();
        $this->info("Archivos locales encontrados: {$records->count()}");
        if (! $this->option('execute')) return self::SUCCESS;

        $failed = 0;
        foreach ($records as $record) {
            try {
                $oldDisk = $record->disk;
                $oldPath = $record->path;
                $disk = Storage::disk($oldDisk);
                if (! $disk->exists($oldPath)) throw new \RuntimeException('El archivo local no existe.');
                $absolutePath = $disk->path($oldPath);
                $upload = new UploadedFile($absolutePath, $record->original_name, $record->mime_type, null, true);
                $publicImage = str_starts_with((string) $record->mime_type, 'image/')
                    && in_array($record->disk, ['public'], true);
                $sourceDirectory = dirname($oldPath) === '.' ? '' : dirname($oldPath);
                $stored = $cloudinary->store($upload, trim('migrated/'.$sourceDirectory, '/'), $publicImage);
                $record->update([...$stored, 'last_error' => null]);
                $disk->delete($oldPath);
                $this->line("Migrado: {$record->original_name}");
            } catch (Throwable $e) {
                $failed++;
                $record->update(['last_error' => $e->getMessage()]);
                $this->error("Falló: {$record->original_name}");
            }
        }
        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
