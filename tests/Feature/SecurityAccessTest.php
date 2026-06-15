<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Certificate;
use App\Models\Evidence;
use App\Models\FileUpload;
use App\Models\Microcredential;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SecurityAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach ([
            'Superadministrador',
            'Recursos Humanos',
            'Calidad Academica',
            'Educacion Continua',
            'Personal',
            'Evaluador',
            'Responsable Area',
            'Profesor',
            'Alumno',
            'Externo',
        ] as $role) {
            Role::findOrCreate($role, 'web');
        }
    }

    public function test_unauthenticated_user_cannot_enter_participant_portal(): void
    {
        $this->get(route('participant.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_sensitive_models_generate_public_ulids_for_routes(): void
    {
        $activity = $this->activity();

        $this->assertNotNull($activity->public_id);
        $this->assertSame('public_id', $activity->getRouteKeyName());
        $this->assertStringContainsString($activity->public_id, route('participant.catalog.show', $activity));
        $this->assertStringNotContainsString('/'.$activity->id, route('participant.catalog.show', $activity));
    }

    public function test_participant_cannot_view_other_users_certificate_or_evidence(): void
    {
        $participant = $this->userWithRole('Alumno');
        $owner = $this->userWithRole('Alumno');
        $activity = $this->activity();
        $certificate = Certificate::create([
            'user_id' => $owner->id,
            'activity_id' => $activity->id,
            'folio' => 'CERT-OTHER',
            'status' => 'emitida',
        ]);
        $evidence = Evidence::create([
            'user_id' => $owner->id,
            'activity_id' => $activity->id,
            'title' => 'Evidencia privada',
        ]);

        $this->assertFalse(Gate::forUser($participant)->allows('view', $certificate));
        $this->assertFalse(Gate::forUser($participant)->allows('view', $evidence));
    }

    public function test_personal_cannot_view_an_unassigned_course(): void
    {
        $personal = $this->userWithRole('Personal');
        $activity = $this->activity(['instructor_id' => null]);

        $this->actingAs($personal)
            ->get(route('personal.courses.show', $activity))
            ->assertForbidden();
    }

    public function test_evaluator_cannot_view_unassigned_evidence(): void
    {
        $evaluator = $this->userWithRole('Evaluador');
        $owner = $this->userWithRole('Alumno');
        $activity = $this->activity();
        $evidence = Evidence::create([
            'user_id' => $owner->id,
            'activity_id' => $activity->id,
            'title' => 'Sin asignar',
            'assigned_evaluator_id' => null,
        ]);

        $this->actingAs($evaluator)
            ->get(route('evaluator.evidences.show', $evidence))
            ->assertForbidden();
    }

    public function test_user_without_role_cannot_enter_filament(): void
    {
        $user = User::factory()->create(['status' => 'activo']);

        $this->actingAs($user)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_public_certificate_verification_does_not_expose_sensitive_data(): void
    {
        $owner = User::factory()->create([
            'status' => 'activo',
            'curp' => 'GODE561231HDFRRN09',
            'email' => 'sensible@example.com',
        ]);
        $certificate = Certificate::create([
            'user_id' => $owner->id,
            'activity_id' => $this->activity()->id,
            'folio' => 'PUBLIC-001',
            'certificate_type' => 'terminacion',
            'issued_at' => now(),
            'status' => 'emitida',
        ]);

        $this->get(route('public.certificates.verify', $certificate->folio))
            ->assertOk()
            ->assertDontSee($owner->curp)
            ->assertDontSee($owner->email)
            ->assertSee($certificate->folio)
            ->assertHeader('X-Content-Type-Options', 'nosniff');
    }

    public function test_private_file_requires_authentication_signature_and_ownership(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('certificates/private.pdf', 'private certificate');

        $owner = $this->userWithRole('Alumno');
        $otherParticipant = $this->userWithRole('Alumno');
        $file = FileUpload::create([
            'original_name' => 'constancia.pdf',
            'stored_name' => 'private.pdf',
            'disk' => 'local',
            'path' => 'certificates/private.pdf',
            'mime_type' => 'application/pdf',
            'uploaded_by' => $owner->id,
        ]);
        Certificate::create([
            'user_id' => $owner->id,
            'activity_id' => $this->activity()->id,
            'file_upload_id' => $file->id,
            'folio' => 'DOWNLOAD-001',
            'status' => 'emitida',
        ]);
        $signedUrl = URL::temporarySignedRoute(
            'files.download',
            now()->addMinutes(5),
            ['fileUpload' => $file],
        );

        $this->get($signedUrl)->assertRedirect(route('login'));
        $this->actingAs($owner)->get(route('files.download', $file))->assertForbidden();
        $this->actingAs($otherParticipant)->get($signedUrl)->assertForbidden();
        $this->actingAs($owner)->get($signedUrl)->assertOk();
    }

    public function test_microcredential_api_requires_signature_and_hides_internal_ids(): void
    {
        $owner = $this->userWithRole('Alumno');
        $microcredential = Microcredential::create([
            'user_id' => $owner->id,
            'activity_id' => $this->activity()->id,
            'name' => 'Competencia segura',
            'status' => 'emitida',
            'issued_at' => now(),
        ]);

        $this->getJson(route('api.v1.microcredentials.show', $microcredential))
            ->assertForbidden();

        $signedUrl = URL::temporarySignedRoute(
            'api.v1.microcredentials.show',
            now()->addMinutes(5),
            ['microcredential' => $microcredential],
        );

        $this->getJson($signedUrl)
            ->assertOk()
            ->assertJsonPath('data.public_id', $microcredential->public_id)
            ->assertJsonPath('data.recipient.public_id', $owner->public_id)
            ->assertJsonMissing(['id' => $microcredential->id])
            ->assertJsonMissing(['id' => $owner->id]);
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create(['status' => 'activo']);
        $user->assignRole($role);

        return $user;
    }

    private function activity(array $attributes = []): Activity
    {
        $activityType = ActivityType::create([
            'name' => 'Curso '.fake()->unique()->word(),
        ]);

        return Activity::create(array_merge([
            'activity_type_id' => $activityType->id,
            'name' => 'Actividad '.fake()->unique()->word(),
            'slug' => fake()->unique()->slug(),
            'duration_hours' => 8,
            'status' => 'publicado',
        ], $attributes));
    }
}
