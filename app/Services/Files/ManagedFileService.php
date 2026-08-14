<?php

namespace App\Services\Files;

use App\Models\FileUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class ManagedFileService
{
    public function __construct(private readonly CloudinaryFileStorage $cloudinary) {}

    public function store(UploadedFile $file, string $directory, ?int $uploadedBy = null, bool $publicImage = false): FileUpload
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $storedName = Str::uuid().($extension ? '.'.$extension : '');
        $stored = null;

        try {
            if ($this->cloudinary->configured()) {
                $stored = $this->cloudinary->store($file, $directory, $publicImage);
            } else {
                $path = $file->storeAs($directory, $storedName, $publicImage ? 'public' : 'local');
                abort_if($path === false, 500, 'No fue posible guardar el archivo.');
                $stored = [
                    'disk' => $publicImage ? 'public' : 'local',
                    'path' => $path,
                    'stored_name' => $storedName,
                    'asset_id' => null,
                    'resource_type' => $publicImage ? 'image' : 'raw',
                    'delivery_type' => $publicImage ? 'upload' : 'private',
                    'version' => null,
                ];
            }

            return FileUpload::create([
                ...$stored,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'extension' => $extension,
                'size' => $file->getSize(),
                'hash' => hash_file('sha256', $file->getRealPath()),
                'uploaded_by' => $uploadedBy,
            ]);
        } catch (Throwable $exception) {
            if (is_array($stored)) {
                $this->deletePhysical($stored['disk'], $stored['path'], $stored['resource_type'], $stored['delivery_type']);
            }
            throw $exception;
        }
    }

    public function replace(?FileUpload $previous, UploadedFile $file, string $directory, ?int $uploadedBy = null, bool $publicImage = false): FileUpload
    {
        $replacement = $this->store($file, $directory, $uploadedBy, $publicImage);
        if ($previous) {
            $this->scheduleDeletion($previous);
        }
        return $replacement;
    }

    public function scheduleDeletion(FileUpload $file, int $days = 30): void
    {
        $file->update(['delete_after' => now()->addDays($days)]);
        $file->delete();
    }

    public function purge(FileUpload $file): void
    {
        $this->deletePhysical($file->disk, $file->path, $file->resource_type, $file->delivery_type);
        $file->forceDelete();
    }

    private function deletePhysical(string $disk, string $path, string $resourceType, string $deliveryType): void
    {
        if ($disk === 'cloudinary') {
            $this->cloudinary->delete($path, $resourceType, $deliveryType);
            return;
        }
        if (array_key_exists($disk, config('filesystems.disks'))) {
            Storage::disk($disk)->delete($path);
        }
    }
}
