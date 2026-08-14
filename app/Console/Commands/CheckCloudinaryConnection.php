<?php

namespace App\Console\Commands;

use Cloudinary\Cloudinary;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Throwable;

class CheckCloudinaryConnection extends Command
{
    protected $signature = 'cloudinary:check {--cleanup : Elimina cualquier residuo de verificaciones anteriores}';

    protected $description = 'Comprueba la conexión con Cloudinary mediante un archivo privado temporal';

    public function handle(): int
    {
        if (! config('services.cloudinary.enabled')
            || blank(config('services.cloudinary.cloud_name'))
            || blank(config('services.cloudinary.api_key'))
            || blank(config('services.cloudinary.api_secret'))) {
            $this->error('La configuración de Cloudinary está incompleta.');

            return self::FAILURE;
        }

        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => config('services.cloudinary.cloud_name'),
                'api_key' => config('services.cloudinary.api_key'),
                'api_secret' => config('services.cloudinary.api_secret'),
            ],
            'url' => ['secure' => true],
        ]);

        $temporaryPath = tempnam(sys_get_temp_dir(), 'cloudinary-check-');
        $publicId = collect([
            config('services.cloudinary.folder'),
            'integration-tests',
            Str::uuid().'.txt',
        ])->filter()->implode('/');
        $uploadedPublicId = null;

        try {
            $ping = $cloudinary->adminApi()->ping();

            if (($ping['status'] ?? null) !== 'ok') {
                throw new \RuntimeException('Cloudinary no respondió correctamente.');
            }

            file_put_contents($temporaryPath, 'Prueba segura de integración Cloudinary');

            $result = $cloudinary->uploadApi()->upload($temporaryPath, [
                'resource_type' => 'raw',
                'type' => 'private',
                'public_id' => $publicId,
                'overwrite' => false,
            ]);

            $uploadedPublicId = $result['public_id'] ?? null;

            if (! is_string($uploadedPublicId) || $uploadedPublicId === '') {
                throw new \RuntimeException('Cloudinary no confirmó la subida temporal.');
            }

            $this->info('Conexión, autenticación y subida privada verificadas correctamente.');

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error('No fue posible completar la verificación: '.$exception->getMessage());

            return self::FAILURE;
        } finally {
            if (is_string($uploadedPublicId) && $uploadedPublicId !== '') {
                $cloudinary->uploadApi()->destroy($uploadedPublicId, [
                    'resource_type' => 'raw',
                    'type' => 'private',
                    'invalidate' => true,
                ]);
            }

            if (is_string($temporaryPath) && file_exists($temporaryPath)) {
                unlink($temporaryPath);
            }

            if ($this->option('cleanup')) {
                $prefix = collect([config('services.cloudinary.folder'), 'integration-tests'])
                    ->filter()
                    ->implode('/').'/';

                $cloudinary->adminApi()->deleteAssetsByPrefix($prefix, [
                    'resource_type' => 'raw',
                    'type' => 'private',
                    'invalidate' => true,
                ]);
            }
        }
    }
}
