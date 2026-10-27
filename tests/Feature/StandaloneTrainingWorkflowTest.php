<?php

namespace Tests\Feature;

use App\Filament\Pages\ProgramControlPage;
use App\Filament\Resources\CertificateResource\Pages\ManageCertificates;
use App\Filament\Resources\EnrollmentResource\Pages\ManageEnrollments;
use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\TrainingProgram;
use App\Models\User;
use App\Services\Files\CloudinaryFileStorage;
use App\Services\Training\TrainingProgramOperationService;
use Database\Seeders\StandaloneTrainingDemoSeeder;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class StandaloneTrainingWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private User $student;

    private User $professor;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        $this->student = $this->userWithRole('Alumno');
        $this->professor = $this->userWithRole('Profesor');
    }

    #[DataProvider('trainingTypes')]
    public function test_an_independent_program_has_a_complete_student_workflow(string $type): void
    {
        $activity = $this->activity($type);
        $survey = $this->survey();
        $question = $survey->questions->first();

        $this->actingAs($this->student)->get(route('participant.catalog.index'))
            ->assertOk()->assertSee($activity->name);
        $this->post(route('participant.catalog.enroll', $activity))
            ->assertRedirectToRoute('participant.my-courses.index');
        $enrollment = Enrollment::sole();
        $this->assertSame('solicitada', $enrollment->status);
        $this->get(route('participant.learning.show', $enrollment))
            ->assertOk()->assertDontSee('Evaluar curso:');
        $this->get(route('participant.surveys.show', [$survey, $activity]))->assertForbidden();

        $this->actingAs($this->professor)->patch(
            route('participant.professor.teaching.enrollments.update', [$activity, $enrollment]),
            ['final_score' => 95, 'completion_status' => 'completado'],
        )->assertStatus(422);
        $this->post(route('participant.professor.teaching.certificates.store', [$activity, $enrollment]), [
            'certificate' => $this->pdf(),
        ])->assertStatus(422);
        $this->patch(route('participant.professor.teaching.enrollments.review', [$activity, $enrollment]), [
            'status' => 'aprobada',
        ])->assertRedirect();
        $this->patch(route('participant.professor.teaching.enrollments.update', [$activity, $enrollment]), [
            'final_score' => 95, 'completion_status' => 'completado',
        ])->assertRedirect();
        $this->assertNotNull($enrollment->fresh()->completed_at);
        $this->post(route('participant.professor.teaching.certificates.store', [$activity, $enrollment]), [
            'certificate' => $this->pdf(),
        ])->assertRedirect();
        $certificate = Certificate::sole();

        $this->actingAs($this->student)->get(route('participant.profile'))
            ->assertOk()->assertSee($activity->name)->assertSee('Completado')
            ->assertSee('95.00')->assertSee('Descargar constancia');
        $this->get(route('participant.my-courses.index'))->assertOk()->assertSee('Ver formación completada');
        $this->get(route('participant.evaluations.index'))->assertOk()->assertSee('95.00');
        $this->get(route('participant.learning.show', $enrollment))
            ->assertOk()->assertSee('Evaluar curso:')->assertSee('Descargar constancia');
        $this->get($certificate->fileUpload->temporaryDownloadUrl())->assertOk()->assertDownload('constancia.pdf');
        $this->get(route('participant.surveys.show', [$survey, $activity]))
            ->assertOk()->assertSee($question->question_text)->assertSee('Enviar evaluación del curso');
        $this->post(route('participant.surveys.store', [$survey, $activity]), [
            'answers' => [['question_public_id' => $question->public_id, 'value' => '5']],
        ])->assertRedirectToRoute('participant.learning.show', $enrollment);
        $this->assertDatabaseHas('survey_responses', [
            'enrollment_id' => $enrollment->id, 'user_id' => $this->student->id, 'activity_id' => $activity->id,
        ]);
        $this->get(route('participant.learning.show', $enrollment))->assertOk()->assertSee('Encuesta respondida:');
        $this->post(route('participant.surveys.store', [$survey, $activity]), [
            'answers' => [['question_public_id' => $question->public_id, 'value' => '1']],
        ])->assertRedirect();
        $this->assertDatabaseCount('survey_responses', 1);
        $this->assertSame('5', SurveyResponse::sole()->answers[0]['value']);
        $this->assertDatabaseCount('learning_path_training_program', 0);
        $this->assertDatabaseCount('learning_path_items', 0);
        $this->assertDatabaseCount('user_learning_paths', 0);

        $this->actingAs($this->userWithRole('Alumno'));
        $this->get(route('participant.profile'))->assertOk()->assertDontSee($activity->name);
        $this->get(route('participant.learning.show', $enrollment))->assertForbidden();
        $this->get($certificate->fileUpload->temporaryDownloadUrl())->assertForbidden();
        $this->get(route('participant.surveys.show', [$survey, $activity]))->assertForbidden();
    }

    public function test_the_administrator_can_publish_approve_grade_and_upload_a_linked_certificate(): void
    {
        $activity = $this->activity('Curso', status: 'borrador');
        $administrator = $this->userWithRole('Superadministrador');
        $this->actingAs($administrator);
        Livewire::test(ProgramControlPage::class, ['record' => $activity->training_program_id])
            ->callAction('configureEnrollment', data: ['instructor_id' => $this->professor->id, 'status' => 'en_inscripcion'])
            ->assertHasNoActionErrors();
        $this->assertSame('en_inscripcion', $activity->fresh()->status);
        $this->actingAs($this->student)->get(route('participant.catalog.index'))->assertOk()->assertSee($activity->name);
        $this->post(route('participant.catalog.enroll', $activity))->assertRedirect();
        $enrollment = Enrollment::sole();

        $this->actingAs($administrator);
        Livewire::withQueryParams(['training_program' => $activity->training_program_id, 'activity' => $activity->id])
            ->test(ManageEnrollments::class)->callTableAction('approve', $enrollment)->assertHasNoTableActionErrors();
        $this->assertSame($administrator->id, $enrollment->fresh()->approved_by);
        Livewire::withQueryParams(['training_program' => $activity->training_program_id, 'activity' => $activity->id])
            ->test(ManageEnrollments::class)->callTableAction('edit', $enrollment, data: [
                'final_score' => 90, 'completion_status' => 'completado',
            ])->assertHasNoTableActionErrors();
        $this->assertSame('90.00', $enrollment->fresh()->final_score);
        $this->assertNotNull($enrollment->fresh()->completed_at);
        Livewire::withQueryParams(['training_program' => $activity->training_program_id, 'activity' => $activity->id])
            ->test(ManageCertificates::class)->callAction('create', data: [
                'enrollment_id' => $enrollment->id, 'file_upload_id' => $this->pdf(),
            ])->assertHasNoActionErrors();
        $certificate = Certificate::sole();
        $this->assertSame($this->student->id, $certificate->user_id);
        $this->assertSame($activity->id, $certificate->activity_id);
        $this->assertSame($enrollment->id, $certificate->enrollment_id);
        $this->assertSame('emitida', $certificate->status);

        Livewire::withQueryParams(['training_program' => $activity->training_program_id, 'activity' => $activity->id])
            ->test(ManageCertificates::class)->callAction('create', data: [
                'enrollment_id' => $enrollment->id, 'file_upload_id' => $this->pdf(),
            ])->assertHasActionErrors(['enrollment_id']);
        $this->assertDatabaseCount('certificates', 1);
        $this->assertDatabaseCount('file_uploads', 1);

        $previousFile = $certificate->fileUpload;
        Livewire::withQueryParams(['training_program' => $activity->training_program_id, 'activity' => $activity->id])
            ->test(ManageCertificates::class)->mountTableAction('edit', $certificate)
            ->assertSet('mountedActions.0.name', 'edit')
            ->setTableActionData(['file_upload_id' => []])->setTableActionData([
                'file_upload_id' => $this->pdf(),
            ])->callMountedTableAction()->assertHasNoTableActionErrors()->assertHasNoErrors();
        $this->assertNotSame($previousFile->id, $certificate->fresh()->file_upload_id);
        $this->assertSoftDeleted('file_uploads', ['id' => $previousFile->id]);
        $this->actingAs($this->student)->get(route('participant.profile'))->assertOk()->assertSee('Descargar constancia');
    }

    public function test_a_survey_validates_required_questions_membership_options_and_completion(): void
    {
        $activity = $this->activity('Taller');
        $enrollment = Enrollment::create([
            'user_id' => $this->student->id, 'activity_id' => $activity->id,
            'status' => 'aprobada', 'completion_status' => 'completado',
        ]);
        $survey = $this->survey();
        $question = $survey->questions->first();
        $otherSurvey = $this->survey(general: false);
        $foreign = $otherSurvey->questions->first();
        $this->actingAs($this->student);
        $url = route('participant.surveys.store', [$survey, $activity]);
        $this->post($url, ['answers' => [['question_public_id' => $question->public_id, 'value' => '6']]])
            ->assertSessionHasErrors('answers.0.value');
        $this->post($url, ['answers' => [['question_public_id' => $foreign->public_id, 'value' => '5']]])
            ->assertSessionHasErrors(['answers', 'answers.0.question_public_id']);
        $this->post($url, ['answers' => [
            ['question_public_id' => $question->public_id, 'value' => '5'],
            ['question_public_id' => $question->public_id, 'value' => '5'],
        ]])->assertSessionHasErrors('answers.0.question_public_id');
        $this->get(route('participant.surveys.show', [$otherSurvey, $activity]))->assertForbidden();
        $enrollment->update(['completion_status' => 'en_progreso']);
        $this->post($url, ['answers' => [['question_public_id' => $question->public_id, 'value' => '5']]])->assertForbidden();
        $this->assertDatabaseCount('survey_responses', 0);
    }

    public function test_administration_cannot_complete_an_unapproved_or_ungraded_enrollment(): void
    {
        $activity = $this->activity('Curso');
        $enrollment = Enrollment::create([
            'user_id' => $this->student->id, 'activity_id' => $activity->id, 'status' => 'solicitada',
        ]);
        $this->actingAs($this->userWithRole('Superadministrador'));
        Livewire::test(ManageEnrollments::class)->callTableAction('edit', $enrollment, data: [
            'completion_status' => 'completado', 'final_score' => 95,
        ])->assertHasTableActionErrors(['completion_status']);
        $enrollment->update(['status' => 'aprobada']);
        Livewire::test(ManageEnrollments::class)->callTableAction('edit', $enrollment, data: [
            'completion_status' => 'completado', 'final_score' => null,
        ])->assertHasTableActionErrors(['completion_status']);
        $this->assertSame('no_iniciado', $enrollment->fresh()->completion_status);
        $this->assertNull($enrollment->fresh()->completed_at);
    }

    public function test_administration_cannot_upload_a_certificate_for_an_incomplete_participant(): void
    {
        $activity = $this->activity('Taller');
        $enrollment = Enrollment::create([
            'user_id' => $this->student->id, 'activity_id' => $activity->id,
            'status' => 'aprobada', 'completion_status' => 'en_progreso',
        ]);
        $this->actingAs($this->userWithRole('Superadministrador'));
        Livewire::withQueryParams(['training_program' => $activity->training_program_id, 'activity' => $activity->id])
            ->test(ManageCertificates::class)->callAction('create', data: [
                'enrollment_id' => $enrollment->id, 'file_upload_id' => $this->pdf(),
            ])->assertHasActionErrors(['enrollment_id']);
        $this->assertDatabaseCount('certificates', 0);
        $this->assertDatabaseCount('file_uploads', 0);
    }

    public function test_multiple_choice_answers_and_optional_text_are_saved_for_the_correct_program(): void
    {
        $activity = $this->activity('Minicurso');
        Enrollment::create([
            'user_id' => $this->student->id, 'activity_id' => $activity->id,
            'status' => 'aprobada', 'completion_status' => 'completado',
        ]);
        $survey = $this->survey(general: false);
        $survey->trainingPrograms()->attach($activity->training_program_id);
        $choice = $survey->questions()->create([
            'question_text' => '¿Recomendarías esta formación?', 'question_type' => 'opcion_multiple',
            'options' => ['si' => 'Sí', 'no' => 'No'], 'is_required' => true,
        ]);
        $optional = $survey->questions()->create([
            'question_text' => 'Comentarios', 'question_type' => 'texto', 'is_required' => false,
        ]);
        $answers = [
            ['question_public_id' => $survey->questions->first()->public_id, 'value' => '4'],
            ['question_public_id' => $choice->public_id, 'value' => 'si'],
            ['question_public_id' => $optional->public_id, 'value' => null],
        ];
        $this->actingAs($this->student)->get(route('participant.surveys.show', [$survey, $activity]))
            ->assertOk()->assertSee('Sí')->assertSee('Comentarios');
        $this->post(route('participant.surveys.store', [$survey, $activity]), ['answers' => $answers])->assertRedirect();
        $this->assertSame($answers, SurveyResponse::sole()->answers);
    }

    public function test_a_completed_student_keeps_access_after_the_course_is_closed(): void
    {
        $activity = $this->activity('Curso', status: 'finalizado');
        Enrollment::create([
            'user_id' => $this->student->id, 'activity_id' => $activity->id,
            'status' => 'aprobada', 'completion_status' => 'completado',
        ]);
        $this->actingAs($this->student)->get(route('participant.catalog.show', $activity))->assertOk();
        $this->actingAs($this->userWithRole('Alumno'))->get(route('participant.catalog.show', $activity))->assertForbidden();
    }

    public function test_a_failed_cloudinary_certificate_upload_keeps_the_grade_and_shows_a_retry_message(): void
    {
        $activity = $this->activity('Curso');
        $enrollment = Enrollment::create([
            'user_id' => $this->student->id, 'activity_id' => $activity->id,
            'status' => 'aprobada', 'completion_status' => 'completado', 'final_score' => 95,
        ]);
        $this->mock(CloudinaryFileStorage::class, function ($mock): void {
            $mock->shouldReceive('configured')->andReturnTrue();
            $mock->shouldReceive('store')->once()->andThrow(new ConnectException(
                'Connection timed out', new Request('POST', 'https://api.cloudinary.com/raw/upload'), null, ['errno' => 28],
            ));
        });

        $this->actingAs($this->professor)->from(route('participant.professor.teaching.show', $activity))
            ->post(route('participant.professor.teaching.certificates.store', [$activity, $enrollment]), [
                'certificate' => $this->pdf(),
            ])->assertRedirectToRoute('participant.professor.teaching.show', $activity)
            ->assertSessionHasErrors('certificate');
        $this->assertSame('95.00', $enrollment->fresh()->final_score);
        $this->assertSame('completado', $enrollment->fresh()->completion_status);
        $this->assertDatabaseCount('certificates', 0);
        $this->assertDatabaseCount('file_uploads', 0);
    }

    public function test_the_demo_creates_only_three_independent_programs_and_is_safe_to_repeat(): void
    {
        $this->seed(StandaloneTrainingDemoSeeder::class);
        $this->seed(StandaloneTrainingDemoSeeder::class);
        $this->assertDatabaseCount('training_programs', 3);
        $this->assertDatabaseCount('activities', 3);
        $this->assertDatabaseCount('learning_paths', 0);
        $this->assertDatabaseCount('learning_path_training_program', 0);
        $this->assertDatabaseCount('enrollments', 0);
        $this->assertSame(3, Activity::where('status', 'publicado')->count());
        $student = User::where('email', 'alumno.flujo@formacion.test')->sole();
        $this->actingAs($student)->get(route('participant.catalog.index'))
            ->assertOk()->assertSee('Curso de prueba: Herramientas digitales')
            ->assertSee('Minicurso de prueba: Organización del estudio')->assertSee('Taller de prueba: Trabajo en equipo');
    }

    public static function trainingTypes(): array
    {
        return [['Curso'], ['Minicurso'], ['Taller']];
    }

    private function userWithRole(string $role): User
    {
        Role::findOrCreate($role);
        $user = User::factory()->create(['status' => 'activo']);
        $user->assignRole($role);

        return $user;
    }

    private function activity(string $type, string $status = 'publicado'): Activity
    {
        $activityType = ActivityType::create(['name' => $type, 'status' => 'activo']);
        $program = TrainingProgram::create([
            'activity_type_id' => $activityType->id, 'name' => $type.' independiente',
            'slug' => strtolower($type).'-independiente', 'duration_hours' => 4,
            'requires_survey' => true, 'generates_certificate' => true,
        ]);
        $activity = app(TrainingProgramOperationService::class)->resolve($program);
        $activity->update(['instructor_id' => $this->professor->id, 'status' => $status]);

        return $activity;
    }

    private function survey(bool $general = true): Survey
    {
        $survey = Survey::create(['name' => 'Evaluación de satisfacción', 'is_general' => $general, 'status' => 'activa']);
        $survey->questions()->create(['question_text' => '¿Cómo evalúas el contenido?', 'question_type' => 'escala', 'is_required' => true]);

        return $survey;
    }

    private function pdf(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent('constancia.pdf', "%PDF-1.4\nConstancia de prueba");
    }
}
