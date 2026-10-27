<?php

namespace Tests\Feature;

use App\Filament\Resources\EnrollmentResource;
use App\Livewire\NotificationBell;
use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\FileUpload;
use App\Models\NotificationLog;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class InAppNotificationTest extends TestCase
{
    use RefreshDatabase;

    private User $student;

    private User $professor;

    private User $administrator;

    protected function setUp(): void
    {
        parent::setUp();
        foreach (['Alumno', 'Profesor', 'Superadministrador'] as $role) {
            Role::findOrCreate($role);
        }
        $this->student = $this->user('Alumno');
        $this->professor = $this->user('Profesor');
        $this->administrator = $this->user('Superadministrador');
        Storage::fake('local');
        Mail::fake();
    }

    #[DataProvider('trainingTypes')]
    public function test_the_training_workflow_sends_private_notifications_without_email(string $type): void
    {
        $activity = $this->activity(['activity_type_id' => ActivityType::create(['name' => $type])->id]);
        $this->actingAs($this->student)->post(route('participant.catalog.enroll', $activity))->assertRedirect();
        $enrollment = Enrollment::sole();
        $request = $this->professor->inAppNotifications()->sole();
        $this->assertSame('enrollment_requested', $request->notification_type);
        $this->assertSame($enrollment->public_id, $request->metadata['enrollment']);
        $this->assertStringContainsString($this->student->name, $request->message);

        $this->post(route('participant.catalog.enroll', $activity))->assertRedirect();
        $this->assertDatabaseCount('notification_logs', 1);

        $this->actingAs($this->professor)->patch(route('participant.professor.teaching.enrollments.review', [$activity, $enrollment]), ['status' => 'aprobada'])->assertRedirect();
        $this->assertSame('enrollment_approved', $this->student->inAppNotifications()->sole()->notification_type);
        $this->patch(route('participant.professor.teaching.enrollments.update', [$activity, $enrollment]), [
            'final_score' => 95, 'completion_status' => 'completado',
        ])->assertRedirect();
        $this->post(route('participant.professor.teaching.certificates.store', [$activity, $enrollment]), [
            'certificate' => UploadedFile::fake()->create('constancia.pdf', 100, 'application/pdf'),
        ])->assertRedirect()->assertSessionHasNoErrors();

        $this->assertEqualsCanonicalizing(['enrollment_approved', 'grade_updated', 'certificate_available'], $this->student->inAppNotifications()->pluck('notification_type')->all());
        $this->assertSame(1, $this->professor->inAppNotifications()->count());
        $certificateNotice = $this->student->inAppNotifications()->where('notification_type', 'certificate_available')->sole();
        $this->actingAs($this->student);
        Livewire::test(NotificationBell::class)->assertViewHas('unreadCount', 3)
            ->assertSee('Formación completada')->assertSee('Constancia disponible')
            ->call('open', $certificateNotice->id)->assertRedirect(route('participant.certificates.index'));
        $this->assertNotNull($certificateNotice->fresh()->read_at);
        Mail::assertNothingOutgoing();
    }

    public function test_a_paid_enrollment_notifies_the_professor_only_after_payment_validation(): void
    {
        $activity = $this->activity(['requires_payment' => true, 'cost' => 100]);
        $this->actingAs($this->student)->post(route('participant.catalog.enroll', $activity))->assertRedirect();
        $this->assertDatabaseCount('notification_logs', 0);
        $enrollment = Enrollment::sole();
        $payment = Payment::create([
            'user_id' => $this->student->id, 'activity_id' => $activity->id,
            'enrollment_id' => $enrollment->id, 'amount' => 100,
        ]);
        $this->actingAs($this->administrator);
        $payment->update(['status' => 'validado']);
        $this->assertSame(1, $this->professor->inAppNotifications()->where('notification_type', 'enrollment_requested')->count());
        $payment->update(['status' => 'validado']);
        $enrollment->refresh()->update(['payment_status' => 'validado']);
        $this->assertDatabaseCount('notification_logs', 1);
    }

    public function test_unchanged_grades_do_not_create_duplicates_and_zero_is_a_valid_grade(): void
    {
        $enrollment = $this->enrollment(['status' => 'aprobada']);
        $enrollment->update(['final_score' => 0, 'completion_status' => 'no_aprobado']);
        $this->assertSame(1, $this->student->inAppNotifications()->where('notification_type', 'grade_updated')->count());
        $enrollment->update(['final_score' => 0, 'completion_status' => 'no_aprobado']);
        $this->assertSame(1, $this->student->inAppNotifications()->where('notification_type', 'grade_updated')->count());
        $this->assertStringContainsString('0.00', $this->student->inAppNotifications()->where('notification_type', 'grade_updated')->sole()->message);
    }

    public function test_rejection_notifies_only_the_participant_with_the_reason(): void
    {
        $enrollment = $this->enrollment();
        $this->actingAs($this->professor)->patch(route('participant.professor.teaching.enrollments.review', [$enrollment->activity, $enrollment]), [
            'status' => 'rechazada', 'reason' => 'Sin cupo disponible',
        ])->assertRedirect();
        $notice = $this->student->inAppNotifications()->sole();
        $this->assertSame('enrollment_rejected', $notice->notification_type);
        $this->assertStringContainsString('Sin cupo disponible', $notice->message);
    }

    public function test_a_certificate_notifies_on_availability_and_replacement_but_not_unrelated_changes(): void
    {
        $enrollment = $this->enrollment(['status' => 'aprobada']);
        $certificate = Certificate::create([
            'user_id' => $this->student->id, 'activity_id' => $enrollment->activity_id,
            'enrollment_id' => $enrollment->id, 'folio' => 'AVISO-001', 'status' => 'pendiente', 'file_upload_id' => $this->pdf()->id,
        ]);
        $this->assertSame(0, $this->student->inAppNotifications()->where('notification_type', 'certificate_available')->count());
        $certificate->update(['status' => 'emitida']);
        $this->assertSame(1, $this->student->inAppNotifications()->where('notification_type', 'certificate_available')->count());
        $certificate->update(['issued_at' => now()]);
        $this->assertSame(1, $this->student->inAppNotifications()->where('notification_type', 'certificate_available')->count());
        $certificate->update(['file_upload_id' => $this->pdf()->id]);
        $this->assertSame(2, $this->student->inAppNotifications()->where('notification_type', 'certificate_available')->count());
        $this->assertSame('Constancia actualizada', $this->student->inAppNotifications()->latest('id')->first()->subject);
        $certificate->update(['file_upload_id' => $certificate->file_upload_id]);
        $this->assertSame(2, $this->student->inAppNotifications()->where('notification_type', 'certificate_available')->count());
    }

    public function test_each_user_can_only_read_and_mark_their_own_in_app_notifications(): void
    {
        $own = $this->notice($this->student, ['subject' => 'Aviso propio']);
        $other = $this->notice($this->professor, ['subject' => 'Aviso privado del profesor']);
        $email = $this->notice($this->student, ['subject' => 'Correo fuera de la campana', 'channel' => 'mail']);
        $pending = $this->notice($this->student, ['subject' => 'Aviso todavía no enviado', 'status' => 'pendiente']);
        $this->actingAs($this->student);
        Livewire::test(NotificationBell::class)->assertViewHas('unreadCount', 1)->assertSee('Aviso propio')
            ->assertDontSee($other->subject)->assertDontSee($email->subject)->assertDontSee($pending->subject);
        Livewire::test(NotificationBell::class)->call('markAsRead', $other->id)->assertNotFound();
        Livewire::test(NotificationBell::class)->call('open', $other->id)->assertNotFound();
        Livewire::test(NotificationBell::class)->call('markAllAsRead')->assertViewHas('unreadCount', 0);
        $this->assertNotNull($own->fresh()->read_at);
        foreach ([$other, $email, $pending] as $notice) {
            $this->assertNull($notice->fresh()->read_at);
        }
    }

    public function test_reading_twice_preserves_the_first_read_time_and_pagination_preserves_ownership(): void
    {
        $oldest = $this->notice($this->student, ['subject' => 'Aviso más antiguo']);
        for ($index = 0; $index < 6; $index++) {
            $this->notice($this->student, ['subject' => 'Aviso reciente '.$index]);
        }
        $this->notice($this->professor, ['subject' => 'Privado de otra persona']);
        $this->actingAs($this->student);
        $bell = Livewire::test(NotificationBell::class)->assertViewHas('unreadCount', 7)->assertDontSee('Aviso más antiguo');
        $bell->call('nextPage', 'notificationsPage')->assertSee('Aviso más antiguo')->assertDontSee('Privado de otra persona');
        $bell->call('markAsRead', $oldest->id);
        $firstRead = $oldest->fresh()->read_at;
        $this->travel(2)->minutes();
        $bell->call('markAsRead', $oldest->id);
        $this->assertTrue($firstRead->equalTo($oldest->fresh()->read_at));
    }

    public function test_notification_destinations_recheck_assignment_and_never_redirect_to_metadata_urls(): void
    {
        $enrollment = $this->enrollment();
        $request = $this->professor->inAppNotifications()->sole();
        $this->actingAs($this->professor);
        Livewire::test(NotificationBell::class)->call('open', $request->id)->assertRedirect(route('participant.professor.teaching.show', $enrollment->activity));
        $enrollment->activity->update(['instructor_id' => null]);
        Livewire::test(NotificationBell::class)->call('open', $request->id)->assertRedirect(route('dashboard'));

        $forgedTarget = $this->notice($this->student, ['metadata' => ['url' => 'https://example.com', 'enrollment' => 'not-owned']]);
        $this->actingAs($this->student);
        Livewire::test(NotificationBell::class)->call('open', $forgedTarget->id)->assertRedirect(route('participant.my-courses.index'));
    }

    public function test_a_request_without_a_professor_is_visible_to_the_administrator(): void
    {
        $activity = $this->activity(['instructor_id' => null]);
        $this->enrollment(['activity_id' => $activity->id]);
        $notice = $this->administrator->inAppNotifications()->sole();
        $this->actingAs($this->administrator);
        Livewire::test(NotificationBell::class, ['admin' => true])->call('open', $notice->id)
            ->assertRedirect(EnrollmentResource::getUrl('index', ['activity' => $activity->id]));
    }

    public function test_rolled_back_actions_do_not_leave_notifications(): void
    {
        try {
            DB::transaction(function (): void {
                $this->enrollment();
                $this->assertDatabaseCount('notification_logs', 1);
                throw new \RuntimeException('Cancelar la operación de prueba');
            });
        } catch (\RuntimeException $exception) {
            $this->assertSame('Cancelar la operación de prueba', $exception->getMessage());
        }
        $this->assertDatabaseCount('notification_logs', 0);
    }

    public function test_an_anonymous_user_cannot_mount_the_notification_component(): void
    {
        Livewire::test(NotificationBell::class)->assertUnauthorized();
    }

    public function test_the_bell_is_available_in_the_participant_and_administrative_headers(): void
    {
        $this->actingAs($this->student)->get(route('participant.profile'))->assertOk()->assertSee('data-notification-bell', false);
        $this->actingAs($this->administrator)->get('/admin')->assertOk()->assertSee('pf-notification-bell-admin');
    }

    public static function trainingTypes(): array
    {
        return [['Curso'], ['Minicurso'], ['Taller']];
    }

    private function user(string $role): User
    {
        $user = User::factory()->create(['status' => 'activo']);
        $user->assignRole($role);

        return $user;
    }

    private function activity(array $attributes = []): Activity
    {
        return Activity::create([
            'activity_type_id' => ActivityType::firstOrCreate(['name' => 'Curso'])->id,
            'instructor_id' => $this->professor->id, 'name' => 'Formación para avisos',
            'slug' => fake()->unique()->slug(), 'status' => 'publicado', 'duration_hours' => 4, 'generates_certificate' => true,
            ...$attributes,
        ]);
    }

    private function enrollment(array $attributes = []): Enrollment
    {
        return Enrollment::create([
            'user_id' => $this->student->id, 'activity_id' => $attributes['activity_id'] ?? $this->activity()->id,
            'status' => 'solicitada', 'requested_at' => now(), ...$attributes,
        ]);
    }

    private function pdf(): FileUpload
    {
        return FileUpload::create([
            'disk' => 'local', 'path' => fake()->uuid().'.pdf', 'original_name' => 'constancia.pdf',
            'stored_name' => 'constancia.pdf', 'extension' => 'pdf', 'mime_type' => 'application/pdf',
        ]);
    }

    private function notice(User $user, array $attributes = []): NotificationLog
    {
        return NotificationLog::create([
            'user_id' => $user->id, 'notification_type' => 'grade_updated', 'subject' => 'Aviso de prueba',
            'message' => 'Un aviso de tu formación.', 'channel' => 'in_app', 'status' => 'enviada', 'sent_at' => now(), ...$attributes,
        ]);
    }
}
