<?php

namespace Tests\Feature;

use App\Filament\Pages\ProgramControlPage;
use App\Filament\Resources\EnrollmentResource\Pages\ManageEnrollments;
use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Enrollment;
use App\Models\LearningPath;
use App\Models\NotificationLog;
use App\Models\TrainingProgram;
use App\Models\User;
use App\Models\UserLearningPath;
use App\Services\Training\TrainingProgramOperationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class OptionalEnrollmentCapacityTest extends TestCase
{
    use RefreshDatabase;

    #[DataProvider('trainingTypes')]
    public function test_empty_capacity_allows_students_and_repeated_requests_never_duplicate_enrollments(string $type): void
    {
        $activity = $this->activity($type);
        $this->assertNull($activity->max_capacity);

        foreach (range(1, 4) as $number) {
            $student = $this->userWithRole('Alumno');
            $this->actingAs($student)->post(route('participant.catalog.enroll', $activity))->assertRedirect();
            $noticeCount = NotificationLog::count();
            $this->post(route('participant.catalog.enroll', $activity))->assertRedirect();
            $this->assertSame($number, $activity->enrollments()->count());
            $this->assertSame($noticeCount, NotificationLog::count());
        }

        $this->assertTrue($activity->hasAvailableCapacity());
    }

    #[DataProvider('trainingTypes')]
    public function test_a_numeric_limit_blocks_new_requests_but_keeps_existing_requests_idempotent(string $type): void
    {
        $activity = $this->activity($type, 1);
        $first = $this->userWithRole('Alumno');
        $this->actingAs($first)->post(route('participant.catalog.enroll', $activity))->assertRedirect();
        $noticeCount = NotificationLog::count();
        $this->post(route('participant.catalog.enroll', $activity))->assertRedirect()->assertSessionHasNoErrors();

        $second = $this->userWithRole('Alumno');
        $url = route('participant.catalog.show', $activity);
        $this->actingAs($second)->get($url)->assertOk()
            ->assertSee('No hay cupos disponibles')->assertDontSee('Solicitar inscripción');
        $this->from($url)->post(route('participant.catalog.enroll', $activity))
            ->assertRedirect($url)->assertSessionHasErrors('activity_id');
        $this->get($url)->assertOk()->assertSee('No hay cupos disponibles');
        $this->assertDatabaseCount('enrollments', 1);
        $this->assertSame($noticeCount, NotificationLog::count());
    }

    public function test_approval_uses_the_reserved_place_and_rejection_or_cancellation_releases_it(): void
    {
        $activity = $this->activity('Curso', 1);
        $professor = User::findOrFail($activity->instructor_id);
        $student = $this->userWithRole('Alumno');
        $this->actingAs($student)->post(route('participant.catalog.enroll', $activity))->assertRedirect();
        $enrollment = Enrollment::sole();

        $this->actingAs($professor)->patch(
            route('participant.professor.teaching.enrollments.review', [$activity, $enrollment]),
            ['status' => 'aprobada'],
        )->assertRedirect()->assertSessionHasNoErrors();
        $this->assertSame('aprobada', $enrollment->fresh()->status);
        $this->assertFalse($activity->hasAvailableCapacity());

        $enrollment->refresh()->update(['status' => 'cancelada']);
        $this->assertTrue($activity->hasAvailableCapacity());
        $other = Enrollment::create([
            'activity_id' => $activity->id, 'user_id' => $this->userWithRole('Alumno')->id,
            'status' => 'solicitada',
        ]);
        $this->actingAs($professor)->patch(
            route('participant.professor.teaching.enrollments.review', [$activity, $other]),
            ['status' => 'rechazada', 'reason' => 'El alumno solicitó retirarse.'],
        )->assertRedirect();
        $this->assertTrue($activity->hasAvailableCapacity());
    }

    public function test_administration_can_set_a_limit_and_remove_it_later(): void
    {
        $activity = $this->activity('Taller');
        $this->actingAs($this->userWithRole('Superadministrador'));
        $page = Livewire::test(ProgramControlPage::class, ['record' => $activity->training_program_id]);
        $page->callAction('configureEnrollment', data: [
            'instructor_id' => $activity->instructor_id, 'status' => 'en_inscripcion', 'max_capacity' => 1,
        ])->assertHasNoActionErrors();
        $this->assertSame(1, $activity->fresh()->max_capacity);

        Enrollment::create(['user_id' => $this->userWithRole('Alumno')->id, 'activity_id' => $activity->id]);
        $this->assertFalse($activity->fresh()->hasAvailableCapacity());
        $page->callAction('configureEnrollment', data: [
            'instructor_id' => $activity->instructor_id, 'status' => 'en_inscripcion', 'max_capacity' => '',
        ])->assertHasNoActionErrors();
        $this->assertNull($activity->fresh()->max_capacity);
        $this->assertTrue($activity->fresh()->hasAvailableCapacity());

        $student = $this->userWithRole('Alumno');
        $this->actingAs($student)->get(route('participant.catalog.show', $activity))
            ->assertOk()->assertSee('Solicitar inscripción');
        $this->post(route('participant.catalog.enroll', $activity))->assertRedirect()->assertSessionHasNoErrors();
        $this->assertDatabaseCount('enrollments', 2);
    }

    public function test_capacity_must_be_a_positive_integer_when_provided(): void
    {
        $activity = $this->activity('Curso');
        $this->actingAs($this->userWithRole('Superadministrador'));

        foreach ([0, -1, 1.5] as $invalid) {
            Livewire::test(ProgramControlPage::class, ['record' => $activity->training_program_id])
                ->callAction('configureEnrollment', data: [
                    'instructor_id' => $activity->instructor_id, 'status' => 'publicado', 'max_capacity' => $invalid,
                ])->assertHasActionErrors(['max_capacity']);
            $this->assertNull($activity->fresh()->max_capacity);
        }
    }

    public function test_administrative_enrollments_also_respect_capacity_and_reject_duplicates(): void
    {
        $activity = $this->activity('Curso', 1);
        $first = $this->userWithRole('Alumno');
        Enrollment::create(['user_id' => $first->id, 'activity_id' => $activity->id, 'status' => 'aprobada']);
        $this->actingAs($this->userWithRole('Superadministrador'));
        $data = [
            'user_id' => $this->userWithRole('Alumno')->id, 'activity_id' => $activity->id,
            'status' => 'aprobada', 'payment_status' => 'no_aplica', 'completion_status' => 'no_iniciado',
        ];
        Livewire::test(ManageEnrollments::class)->callAction('create', data: $data)
            ->assertHasActionErrors(['activity_id']);
        Livewire::test(ManageEnrollments::class)->callAction('create', data: [...$data, 'user_id' => $first->id])
            ->assertHasActionErrors(['user_id']);
        $this->assertDatabaseCount('enrollments', 1);
    }

    public function test_a_cancelled_enrollment_cannot_be_reactivated_when_its_place_is_taken(): void
    {
        $activity = $this->activity('Minicurso', 1);
        $cancelled = Enrollment::create([
            'activity_id' => $activity->id, 'user_id' => $this->userWithRole('Alumno')->id, 'status' => 'cancelada',
        ]);
        Enrollment::create(['activity_id' => $activity->id, 'user_id' => $this->userWithRole('Alumno')->id]);

        try {
            $cancelled->update(['status' => 'aprobada']);
            $this->fail('Una reactivación no debe exceder el cupo.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('activity_id', $exception->errors());
        }

        $this->assertSame('cancelada', $cancelled->fresh()->status);
    }

    public function test_a_path_with_a_full_activity_does_not_leave_a_partial_assignment_or_notifications(): void
    {
        $first = $this->activity('Curso');
        $full = $this->activity('Taller', 1);
        Enrollment::create(['activity_id' => $full->id, 'user_id' => $this->userWithRole('Alumno')->id]);
        $path = LearningPath::create(['name' => 'Ruta de prueba', 'slug' => 'ruta-cupo', 'status' => 'publicada']);
        $path->trainingPrograms()->attach($first->training_program_id, ['order_number' => 1]);
        $path->trainingPrograms()->attach($full->training_program_id, ['order_number' => 2]);
        $student = $this->userWithRole('Alumno');
        $noticeCount = NotificationLog::count();

        try {
            UserLearningPath::create(['user_id' => $student->id, 'learning_path_id' => $path->id]);
            $this->fail('No debe asignarse parcialmente una ruta con una actividad llena.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('activity_id', $exception->errors());
        }

        $this->assertDatabaseCount('user_learning_paths', 0);
        $this->assertDatabaseCount('enrollments', 1);
        $this->assertSame($noticeCount, NotificationLog::count());
    }

    public static function trainingTypes(): array
    {
        return [['Curso'], ['Minicurso'], ['Taller']];
    }

    private function activity(string $type, ?int $capacity = null): Activity
    {
        $activityType = ActivityType::create(['name' => $type, 'status' => 'activo']);
        $program = TrainingProgram::create([
            'activity_type_id' => $activityType->id, 'name' => $type.' con cupo opcional',
            'slug' => strtolower($type).'-cupo-opcional', 'duration_hours' => 4,
        ]);
        $activity = app(TrainingProgramOperationService::class)->resolve($program);
        $activity->update([
            'status' => 'publicado', 'instructor_id' => $this->userWithRole('Profesor')->id, 'max_capacity' => $capacity,
        ]);

        return $activity->refresh();
    }

    private function userWithRole(string $role): User
    {
        Role::findOrCreate($role);
        $user = User::factory()->create(['status' => 'activo']);
        $user->assignRole($role);

        return $user;
    }
}
