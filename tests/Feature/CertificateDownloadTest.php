<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\FileUpload;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CertificateDownloadTest extends TestCase
{
    use RefreshDatabase;

    private const PDF = "%PDF-1.4\nContenido de la constancia";

    protected function setUp(): void
    {
        parent::setUp();
        Role::findOrCreate('Alumno');
        $student = User::factory()->create(['status' => 'activo']);
        $student->assignRole('Alumno');
        $this->actingAs($student);
        config()->set('services.cloudinary', [
            'enabled' => true, 'cloud_name' => 'test-cloud', 'api_key' => 'test-key', 'api_secret' => 'test-secret',
        ]);
        Http::preventStrayRequests();
    }

    #[DataProvider('downloadNames')]
    public function test_cloudinary_certificates_download_as_pdf_even_if_the_remote_filename_is_temporary(string $originalName, string $expectedName): void
    {
        $file = $this->certificateFile('cloudinary', $originalName);
        Http::fake(['api.cloudinary.com/*' => Http::response(self::PDF, 200, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="random.pdf.tmp"',
        ])]);

        $response = $this->get($file->temporaryDownloadUrl())->assertOk()->assertDownload()
            ->assertHeader('Content-Type', 'application/pdf')->assertStreamedContent(self::PDF);

        if (mb_check_encoding($expectedName, 'ASCII')) {
            $response->assertDownload($expectedName);
        } else {
            $this->assertStringContainsString("filename*=utf-8''".rawurlencode($expectedName), $response->headers->get('Content-Disposition'));
        }
    }

    public function test_a_local_legacy_certificate_also_keeps_its_pdf_extension(): void
    {
        Storage::fake('local');
        $file = $this->certificateFile('local', 'constancia.pdf.tmp');
        Storage::disk('local')->put($file->path, self::PDF);

        $this->get($file->temporaryDownloadUrl())->assertOk()->assertDownload('constancia.pdf');
    }

    public function test_a_different_student_cannot_download_the_certificate_or_contact_cloudinary(): void
    {
        $file = $this->certificateFile('cloudinary', 'constancia.pdf');
        $other = User::factory()->create(['status' => 'activo']);
        $other->assignRole('Alumno');
        Http::fake();

        $this->actingAs($other)->get($file->temporaryDownloadUrl())->assertForbidden();
        Http::assertNothingSent();
    }

    public function test_a_cloudinary_timeout_is_reported_as_a_failed_download(): void
    {
        $file = $this->certificateFile('cloudinary', 'constancia.pdf');
        Http::fake(fn () => throw new ConnectionException('Connection timed out'));

        $this->get($file->temporaryDownloadUrl())->assertStatus(503);
    }

    public function test_a_cloudinary_error_is_not_downloaded_as_a_pdf(): void
    {
        $file = $this->certificateFile('cloudinary', 'constancia.pdf');
        Http::fake(['api.cloudinary.com/*' => Http::response('Not found', 404)]);

        $this->get($file->temporaryDownloadUrl())->assertStatus(502);
    }

    public static function downloadNames(): array
    {
        return [
            ['Constancia de terminación.pdf', 'Constancia de terminación.pdf'],
            ['constancia.pdf.tmp', 'constancia.pdf'],
            ['constancia.tmp', 'constancia.pdf'],
        ];
    }

    private function certificateFile(string $disk, string $name): FileUpload
    {
        $file = FileUpload::create([
            'disk' => $disk, 'path' => 'certificates/random.pdf', 'stored_name' => 'random.pdf',
            'original_name' => $name, 'mime_type' => 'application/pdf', 'extension' => 'pdf',
            'resource_type' => 'raw', 'delivery_type' => 'private',
        ]);
        Certificate::create(['user_id' => auth()->id(), 'file_upload_id' => $file->id, 'folio' => 'TEST-001', 'status' => 'emitida']);

        return $file;
    }
}
