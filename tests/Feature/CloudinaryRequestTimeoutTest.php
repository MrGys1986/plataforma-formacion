<?php

namespace Tests\Feature;

use App\Services\Files\CloudinaryFileStorage;
use Cloudinary\Cloudinary;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionProperty;
use Tests\TestCase;

class CloudinaryRequestTimeoutTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.cloudinary', [
            'enabled' => true,
            'cloud_name' => 'test-cloud',
            'api_key' => 'test-key',
            'api_secret' => 'test-secret',
            'folder' => 'tests',
        ]);
    }

    #[DataProvider('uploadTypes')]
    public function test_storage_uploads_and_deletions_use_bounded_sdk_requests(bool $publicImage, string $type, string $delivery, string $extension): void
    {
        $uploadApi = app(Cloudinary::class)->uploadApi();
        $history = [];
        $this->interceptRequests($uploadApi, [
            new Response(200, [], json_encode([
                'public_id' => 'tests/example', 'resource_type' => $type, 'type' => $delivery,
            ])),
            new Response(200, [], '{"result":"ok"}'),
        ], $history);
        $this->mock(Cloudinary::class)->shouldReceive('uploadApi')->twice()->andReturn($uploadApi);

        $storage = app(CloudinaryFileStorage::class);
        $asset = $storage->store(UploadedFile::fake()->createWithContent('example.'.$extension, 'test upload'), 'covers', $publicImage);
        $storage->delete($asset['path'], $asset['resource_type'], $asset['delivery_type']);

        $this->assertSame($type, $asset['resource_type']);
        $this->assertSame($delivery, $asset['delivery_type']);
        $this->assertCount(2, $history);
        $this->assertSame('/v1_1/test-cloud/'.$type.'/upload', $history[0]['request']->getUri()->getPath());
        $this->assertSame('/v1_1/test-cloud/'.$type.'/destroy', $history[1]['request']->getUri()->getPath());
        $this->assertBoundedRequests($history);
    }

    public function test_the_connection_check_also_limits_ping_upload_and_cleanup_requests(): void
    {
        $cloudinary = app(Cloudinary::class);
        $adminApi = $cloudinary->adminApi();
        $uploadApi = $cloudinary->uploadApi();
        $history = [];
        $this->interceptRequests($adminApi, [new Response(200, [], '{"status":"ok"}')], $history);
        $this->interceptRequests($uploadApi, [
            new Response(200, [], '{"public_id":"tests/check.txt"}'),
            new Response(200, [], '{"result":"ok"}'),
        ], $history);
        $this->mock(Cloudinary::class, function ($mock) use ($adminApi, $uploadApi): void {
            $mock->shouldReceive('adminApi')->once()->andReturn($adminApi);
            $mock->shouldReceive('uploadApi')->twice()->andReturn($uploadApi);
        });

        $this->artisan('cloudinary:check')->assertSuccessful();

        $this->assertCount(3, $history);
        $this->assertBoundedRequests($history);
    }

    public static function uploadTypes(): array
    {
        return [
            'public image' => [true, 'image', 'upload', 'jpg'],
            'private document' => [false, 'raw', 'private', 'pdf'],
        ];
    }

    private function interceptRequests(object $api, array $responses, array &$history): void
    {
        $client = (new ReflectionProperty($api, 'apiClient'))->getValue($api);
        $handler = HandlerStack::create(new MockHandler($responses));
        $handler->push(Middleware::history($history));

        // Keep the SDK's real HTTP defaults and upload options; replace only the network transport.
        $client->httpClient = new Client([
            ...$client->httpClient->getConfig(),
            'handler' => $handler,
        ]);
    }

    private function assertBoundedRequests(array $history): void
    {
        foreach ($history as $transaction) {
            $this->assertSame(5, $transaction['options']['connect_timeout']);
            $this->assertSame(15, $transaction['options']['timeout']);
        }
    }
}
