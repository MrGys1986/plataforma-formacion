<?php

namespace Tests\Feature;

use App\Filament\Pages\StorageDashboard;
use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Enrollment;
use App\Models\FileUpload;
use App\Models\Payment;
use App\Models\TrainingProgram;
use App\Models\User;
use App\Services\Files\ManagedFileService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ManagedStorageFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_pdf_word_and_jpg_are_stored_by_the_managed_service(): void
    {
        Storage::fake('local');
        Storage::fake('public');
        config()->set('services.cloudinary.enabled', false);
        $service = app(ManagedFileService::class);

        foreach ([
            UploadedFile::fake()->create('evidencia.pdf', 40, 'application/pdf'),
            UploadedFile::fake()->create('documento.docx', 40, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
        ] as $document) {
            $stored = $service->store($document, 'tests/documents');
            $this->assertSame('local', $stored->disk);
            Storage::disk('local')->assertExists($stored->path);
        }

        $image = $service->store(UploadedFile::fake()->image('portada.jpg', 1200, 630), 'tests/images', publicImage: true);
        $this->assertSame('public', $image->disk);
        $this->assertSame('image', $image->resource_type);
        Storage::disk('public')->assertExists($image->path);
    }

    public function test_external_participant_can_upload_a_payment_proof(): void
    {
        Storage::fake('local');
        Role::findOrCreate('Externo');
        $user = User::factory()->create(['status' => 'activo']);
        $user->assignRole('Externo');
        $type = ActivityType::create(['name' => 'Curso', 'status' => 'activo']);
        $program = TrainingProgram::create(['activity_type_id' => $type->id, 'name' => 'Curso externo', 'slug' => 'curso-externo', 'status' => 'activo']);
        $activity = Activity::create(['training_program_id' => $program->id, 'activity_type_id' => $type->id, 'name' => 'Curso externo E1', 'slug' => 'curso-externo-e1', 'status' => 'publicado', 'duration_hours' => 8, 'is_external' => true, 'requires_payment' => true, 'cost' => 500]);
        $this->actingAs($user)
            ->post(route('participant.catalog.enroll', $activity))
            ->assertRedirect(route('participant.catalog.show', $activity));

        $enrollment = Enrollment::query()
            ->whereBelongsTo($user)
            ->whereBelongsTo($activity)
            ->firstOrFail();

        $this->assertSame('solicitada', $enrollment->status);
        $this->assertNull($enrollment->requested_at);

        $this->actingAs($user)->post(route('participant.payments.store'), [
            'activity_public_id' => $activity->public_id,
            'enrollment_public_id' => $enrollment->public_id,
            'amount' => 500,
            'currency' => 'MXN',
            'payment_method' => 'transferencia',
            'proof' => UploadedFile::fake()->create('comprobante.pdf', 50, 'application/pdf'),
        ])->assertRedirect();

        $this->assertDatabaseHas('payments', ['user_id' => $user->id, 'status' => 'pendiente']);
        $this->assertDatabaseHas('enrollments', ['id' => $enrollment->id, 'status' => 'solicitada', 'payment_status' => 'pendiente']);

        $payment = Payment::query()->where('enrollment_id', $enrollment->id)->firstOrFail();
        $payment->update(['status' => 'validado']);

        $this->assertDatabaseHas('enrollments', ['id' => $enrollment->id, 'status' => 'solicitada', 'payment_status' => 'validado']);
        $this->assertNotNull($enrollment->fresh()->requested_at);
        $this->assertDatabaseHas('file_uploads', ['original_name' => 'comprobante.pdf', 'disk' => 'local']);

        $this->actingAs($user)
            ->get(route('participant.payments.index'))
            ->assertOk()
            ->assertSee('Mis pagos')
            ->assertSee('Curso externo E1')
            ->assertSee('comprobante', false);
    }

    public function test_superadministrator_can_see_every_payment_while_participants_only_see_their_own(): void
    {
        foreach (['Superadministrador', 'Externo'] as $role) {
            Role::findOrCreate($role);
        }

        $administrator = User::factory()->create(['status' => 'activo']);
        $administrator->assignRole('Superadministrador');
        $firstParticipant = User::factory()->create(['status' => 'activo', 'user_type' => 'externo']);
        $firstParticipant->assignRole('Externo');
        $secondParticipant = User::factory()->create(['status' => 'activo', 'user_type' => 'externo']);
        $secondParticipant->assignRole('Externo');

        Payment::create(['user_id' => $firstParticipant->id, 'amount' => 100, 'currency' => 'MXN', 'status' => 'pendiente']);
        Payment::create(['user_id' => $firstParticipant->id, 'amount' => 200, 'currency' => 'MXN', 'status' => 'validado']);
        Payment::create(['user_id' => $secondParticipant->id, 'amount' => 300, 'currency' => 'MXN', 'status' => 'pendiente']);

        $this->assertSame(3, Payment::query()->visibleTo($administrator)->count());
        $this->assertSame(2, Payment::query()->visibleTo($firstParticipant)->count());
        $this->assertSame(1, Payment::query()->visibleTo($secondParticipant)->count());
    }

    public function test_replaced_files_enter_retention_before_physical_deletion(): void
    {
        Storage::fake('local');
        $service = app(ManagedFileService::class);
        $old = $service->store(UploadedFile::fake()->create('anterior.pdf', 10, 'application/pdf'), 'tests');
        $new = $service->replace($old, UploadedFile::fake()->create('nuevo.pdf', 10, 'application/pdf'), 'tests');

        $this->assertNotNull(FileUpload::withTrashed()->find($old->id)?->deleted_at);
        $this->assertNotNull(FileUpload::withTrashed()->find($old->id)?->delete_after);
        Storage::disk('local')->assertExists($old->path);
        Storage::disk('local')->assertExists($new->path);
    }

    public function test_professor_can_update_the_avatar_from_the_profile(): void
    {
        Storage::fake('public');
        Role::findOrCreate('Profesor');
        $user = User::factory()->create(['status' => 'activo']);
        $user->assignRole('Profesor');

        $this->actingAs($user)->post(route('participant.professor.profile.avatar'), [
            'avatar' => UploadedFile::fake()->image('perfil.jpg', 500, 500),
        ])->assertRedirect()->assertSessionHas('status');

        $user->refresh();
        $this->assertNotNull($user->avatar_file_id);
        $this->assertSame('image', $user->avatarFile->resource_type);
        Storage::disk('public')->assertExists($user->avatarFile->path);

        $this->actingAs($user)->get(route('participant.professor.profile'))
            ->assertOk()
            ->assertSee('Cambiar fotografía de perfil');
    }

    public function test_cloudinary_image_url_requests_automatic_format_and_quality(): void
    {
        config()->set('services.cloudinary.cloud_name', 'demo-cloud');
        $file = new FileUpload(['disk' => 'cloudinary', 'resource_type' => 'image', 'path' => 'course-covers/demo.jpg']);
        $url = $file->optimizedImageUrl(960, 420);
        $this->assertStringContainsString('f_auto,q_auto,c_fill,g_auto,w_960,h_420', $url);
    }

    public function test_storage_dashboard_is_available_to_an_administrator(): void
    {
        Role::findOrCreate('Superadministrador');
        $user = User::factory()->create(['status' => 'activo']);
        $user->assignRole('Superadministrador');
        $this->actingAs($user)->get(StorageDashboard::getUrl())->assertOk()->assertSee('Panel de almacenamiento');
    }
}
