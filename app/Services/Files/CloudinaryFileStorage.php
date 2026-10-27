<?php

namespace App\Services\Files;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use RuntimeException;

class CloudinaryFileStorage
{
    public function configured(): bool
    {
        return config('services.cloudinary.enabled')
            && filled(config('services.cloudinary.cloud_name'))
            && filled(config('services.cloudinary.api_key'))
            && filled(config('services.cloudinary.api_secret'));
    }

    /** @return array{disk: string, path: string, stored_name: string, asset_id: ?string, resource_type: string, delivery_type: string, version: ?int} */
    public function store(UploadedFile $file, string $directory, bool $publicImage = false): array
    {
        if (! $this->configured()) {
            throw new RuntimeException('Cloudinary no está configurado completamente.');
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $storedName = Str::uuid().($extension ? '.'.$extension : '');
        $folder = collect([
            config('services.cloudinary.folder'),
            trim($directory, '/'),
        ])->filter()->implode('/');

        $resourceType = $publicImage ? 'image' : 'raw';
        $deliveryType = $publicImage ? 'upload' : 'private';
        $response = $this->client()->uploadApi()->upload($file->getRealPath(), [
            'resource_type' => $resourceType,
            'type' => $deliveryType,
            'folder' => $folder,
            'public_id' => $publicImage ? pathinfo($storedName, PATHINFO_FILENAME) : $storedName,
            'filename_override' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'use_filename' => false,
            'unique_filename' => false,
            'overwrite' => false,
        ]);

        $publicId = $response['public_id'] ?? null;

        if (! is_string($publicId) || $publicId === '') {
            throw new RuntimeException('Cloudinary no devolvió un identificador para el archivo.');
        }

        return [
            'disk' => 'cloudinary',
            'path' => $publicId,
            'stored_name' => $storedName,
            'asset_id' => $response['asset_id'] ?? null,
            'resource_type' => $response['resource_type'] ?? $resourceType,
            'delivery_type' => $response['type'] ?? $deliveryType,
            'version' => isset($response['version']) ? (int) $response['version'] : null,
        ];
    }

    public function temporaryDownloadUrl(string $publicId, string $extension, int $minutes, string $resourceType = 'raw', string $deliveryType = 'private'): string
    {
        return $this->client()->uploadApi()->privateDownloadUrl($publicId, $extension, [
            'resource_type' => $resourceType,
            'type' => $deliveryType,
            'attachment' => true,
            'expires_at' => now()->addMinutes($minutes)->timestamp,
        ]);
    }

    public function temporaryPreviewUrl(string $publicId, string $extension, int $minutes, string $resourceType = 'raw', string $deliveryType = 'private'): string
    {
        return $this->client()->uploadApi()->privateDownloadUrl($publicId, $extension, [
            'resource_type' => $resourceType,
            'type' => $deliveryType,
            'expires_at' => now()->addMinutes($minutes)->timestamp,
        ]);
    }

    public function delete(string $publicId, string $resourceType = 'raw', string $deliveryType = 'private'): void
    {
        if (! $this->configured()) {
            return;
        }

        $this->client()->uploadApi()->destroy($publicId, [
            'resource_type' => $resourceType,
            'type' => $deliveryType,
            'invalidate' => true,
        ]);
    }

    private function client(): Cloudinary
    {
        return app(Cloudinary::class);
    }
}
