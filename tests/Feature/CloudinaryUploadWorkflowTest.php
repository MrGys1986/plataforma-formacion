<?php

namespace Tests\Feature;

use App\Filament\Resources\DigitalResourceResource\Pages\ManageDigitalResources;
use App\Filament\Resources\LearningPathResource\Pages\ManageLearningPaths;
use App\Filament\Resources\TrainingProgramResource\Pages\ManageTrainingPrograms;
use App\Models\ActivityType;
use App\Models\DigitalResource;
use App\Models\FileUpload;
use App\Models\LearningPath;
use App\Models\TrainingProgram;
use App\Models\User;
use App\Services\Files\CloudinaryFileStorage;
use App\Services\Files\ManagedFileService;
use Cloudinary\Api\Exception\AuthorizationRequired;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CloudinaryUploadWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::findOrCreate('Superadministrador');
        $administrator = User::factory()->create(['status' => 'activo']);
        $administrator->assignRole('Superadministrador');
        $this->actingAs($administrator);
    }

    public function test_the_learning_path_form_uploads_a_public_image_to_cloudinary(): void
    {
        $this->mock(CloudinaryFileStorage::class, function ($mock): void {
            $mock->shouldReceive('configured')->andReturnTrue();
            $mock->shouldReceive('store')->once()
                ->withArgs(fn ($file, $directory, $publicImage): bool => $file instanceof UploadedFile
                    && $directory === 'learning-path-covers' && $publicImage === true)
                ->andReturn($this->storedAsset('image', 'upload', 'learning-path-covers/portada'));
        });

        Livewire::test(ManageLearningPaths::class)
            ->callTableAction('create', data: [
                'name' => 'Ruta Cloudinary', 'slug' => 'ruta-cloudinary', 'status' => 'publicada',
                'cover_file_id' => UploadedFile::fake()->image('portada.jpg'),
            ])->assertHasNoTableActionErrors();

        $cover = LearningPath::where('slug', 'ruta-cloudinary')->firstOrFail()->coverFile;
        $this->assertSame('cloudinary', $cover->disk);
        $this->assertSame('image', $cover->resource_type);
        $this->assertSame('upload', $cover->delivery_type);
    }

    public function test_the_document_form_uploads_a_private_pdf_to_cloudinary(): void
    {
        $this->mock(CloudinaryFileStorage::class, function ($mock): void {
            $mock->shouldReceive('configured')->andReturnTrue();
            $mock->shouldReceive('store')->once()
                ->withArgs(fn ($file, $directory, $publicImage): bool => $file instanceof UploadedFile
                    && $directory === 'digital-resources' && $publicImage === false)
                ->andReturn($this->storedAsset('raw', 'private', 'digital-resources/guia.pdf'));
        });

        Livewire::test(ManageDigitalResources::class)->callAction('create', data: [
            'title' => 'Documento de prueba',
            'file_upload_id' => UploadedFile::fake()->createWithContent('guia.pdf', "%PDF-1.4\nDocumento de prueba"),
            'visibility' => 'interno', 'status' => 'activo',
        ])->assertHasNoActionErrors();

        $file = DigitalResource::where('title', 'Documento de prueba')->firstOrFail()->fileUpload;
        $this->assertSame('cloudinary', $file->disk);
        $this->assertSame('raw', $file->resource_type);
        $this->assertSame('private', $file->delivery_type);
    }

    public function test_word_documents_use_private_storage(): void
    {
        $this->mock(CloudinaryFileStorage::class, function ($mock): void {
            $mock->shouldReceive('configured')->andReturnTrue();
            $mock->shouldReceive('store')->once()
                ->withArgs(fn ($file, $directory, $publicImage): bool => $publicImage === false)
                ->andReturn($this->storedAsset('raw', 'private', 'documents/guia.docx'));
        });

        $file = app(ManagedFileService::class)->store(
            UploadedFile::fake()->create('guia.docx', 10, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
            'documents', auth()->id(),
        );

        $this->assertSame('docx', $file->extension);
        $this->assertSame('private', $file->delivery_type);
    }

    public function test_cloudinary_download_and_preview_use_the_stored_asset_type(): void
    {
        config()->set('services.cloudinary', [
            'enabled' => true, 'cloud_name' => 'test-cloud',
            'api_key' => 'test-key', 'api_secret' => 'test-secret',
        ]);
        Http::fake(fn () => Http::response('archivo de prueba'));

        foreach ([['image', 'upload', 'jpg', 'image/jpeg'], ['raw', 'private', 'pdf', 'application/pdf']] as [$type, $delivery, $extension, $mime]) {
            $file = FileUpload::create([
                ...$this->storedAsset($type, $delivery, 'tests/archivo'.($type === 'raw' ? '.pdf' : '')),
                'original_name' => 'archivo.'.$extension, 'extension' => $extension,
                'mime_type' => $mime, 'uploaded_by' => auth()->id(),
            ]);

            $this->get($file->temporaryDownloadUrl())->assertOk()->assertDownload('archivo.'.$extension)
                ->assertHeader('Content-Type', $mime)->assertStreamedContent('archivo de prueba');
            Http::assertSent(function ($request) use ($file, $type, $delivery): bool {
                parse_str(parse_url($request->url(), PHP_URL_QUERY), $query);

                return str_contains($request->url(), '/'.$type.'/download?')
                    && $query['type'] === $delivery && $query['public_id'] === $file->path;
            });

            $response = $this->get($file->temporaryPreviewUrl())->assertRedirect();
            $target = $response->headers->get('Location');
            $this->assertStringContainsString('/'.$type.'/download?', $target);
            parse_str(parse_url($target, PHP_URL_QUERY), $query);
            $this->assertSame($delivery, $query['type']);
            $this->assertSame($file->path, $query['public_id']);
        }
    }

    public function test_a_course_upload_connection_failure_preserves_the_form_and_allows_retrying(): void
    {
        $attempt = 0;
        $this->mock(CloudinaryFileStorage::class, function ($mock) use (&$attempt): void {
            $mock->shouldReceive('configured')->andReturnTrue();
            $mock->shouldReceive('store')->twice()->andReturnUsing(function () use (&$attempt): array {
                if (++$attempt === 1) {
                    throw new ConnectException('Could not resolve host: api.cloudinary.com', new Request('POST', 'https://api.cloudinary.com/image/upload'));
                }

                return $this->storedAsset('image', 'upload', 'course-covers/portada');
            });
        });
        $type = ActivityType::create(['name' => 'Curso', 'status' => 'activo']);

        $page = Livewire::test(ManageTrainingPrograms::class)
            ->callTableAction('create', data: [
                'activity_type_id' => $type->id, 'name' => 'Curso con portada',
                'slug' => 'curso-con-portada', 'duration_hours' => 4,
                'cover_file_id' => UploadedFile::fake()->image('curso.jpg'),
            ])
            ->assertHasTableActionErrors(['cover_file_id'])
            ->assertTableActionMounted('create')
            ->assertTableActionDataSet(['name' => 'Curso con portada', 'slug' => 'curso-con-portada']);

        $this->assertDatabaseCount('training_programs', 0);
        $this->assertDatabaseCount('file_uploads', 0);

        $page->callMountedTableAction()->assertHasNoTableActionErrors();

        $program = TrainingProgram::where('slug', 'curso-con-portada')->sole();
        $this->assertSame('cloudinary', $program->coverFile->disk);
        $this->assertSame('curso.jpg', $program->coverFile->original_name);
        $this->assertDatabaseCount('training_programs', 1);
        $this->assertDatabaseCount('file_uploads', 1);
    }

    public function test_cloudinary_api_rejections_are_shown_on_the_document_field(): void
    {
        $this->mock(CloudinaryFileStorage::class, function ($mock): void {
            $mock->shouldReceive('configured')->andReturnTrue();
            $mock->shouldReceive('store')->once()->andThrow(new AuthorizationRequired('Invalid API credentials'));
        });

        Livewire::test(ManageDigitalResources::class)->callAction('create', data: [
            'title' => 'Documento pendiente',
            'file_upload_id' => UploadedFile::fake()->createWithContent('guia.pdf', "%PDF-1.4\nDocumento de prueba"),
        ])->assertHasActionErrors(['file_upload_id'])->assertActionMounted('create');

        $this->assertDatabaseCount('digital_resources', 0);
        $this->assertDatabaseCount('file_uploads', 0);
    }

    public function test_a_failed_cover_replacement_keeps_the_previous_image_and_course_data(): void
    {
        Storage::fake('public');
        $cover = app(ManagedFileService::class)->store(UploadedFile::fake()->image('anterior.jpg'), 'course-covers', auth()->id(), true);
        $type = ActivityType::create(['name' => 'Curso', 'status' => 'activo']);
        $program = TrainingProgram::create([
            'activity_type_id' => $type->id, 'name' => 'Curso original', 'slug' => 'curso-original',
            'duration_hours' => 4, 'cover_file_id' => $cover->id,
        ]);
        $this->mock(CloudinaryFileStorage::class, function ($mock): void {
            $mock->shouldReceive('configured')->andReturnTrue();
            $mock->shouldReceive('store')->once()->andThrow(new ConnectException(
                'Connection timed out', new Request('POST', 'https://api.cloudinary.com/image/upload'), null, ['errno' => 28],
            ));
        });

        $page = Livewire::test(ManageTrainingPrograms::class)->callTableAction('edit', $program, data: [
            'name' => 'Cambio sin guardar', 'cover_file_id' => UploadedFile::fake()->image('nueva.jpg'),
        ])->assertHasTableActionErrors(['cover_file_id']);

        $this->assertStringContainsString('Cloudinary tardó demasiado en responder.', $page->getMountedActionModalHtml());

        $this->assertSame('Curso original', $program->fresh()->name);
        $this->assertSame($cover->id, $program->fresh()->cover_file_id);
        $this->assertNotSoftDeleted($cover);
        Storage::disk('public')->assertExists($cover->path);
        $this->assertDatabaseCount('file_uploads', 1);
    }

    private function storedAsset(string $resourceType, string $deliveryType, string $path): array
    {
        return [
            'disk' => 'cloudinary', 'path' => $path, 'stored_name' => basename($path),
            'asset_id' => 'test-asset', 'resource_type' => $resourceType,
            'delivery_type' => $deliveryType, 'version' => 123,
        ];
    }
}
