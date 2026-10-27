<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\FileUpload;
use App\Models\TrainingProgram;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class StudentProfileTest extends TestCase
{
    use RefreshDatabase;

    private User $student;

    protected function setUp(): void
    {
        parent::setUp();
        Role::findOrCreate('Alumno');
        $this->student = User::factory()->create(['status' => 'activo']);
        $this->student->assignRole('Alumno');
        $this->actingAs($this->student);
    }

    #[DataProvider('coverSources')]
    public function test_completed_training_displays_the_actual_course_cover(bool $hasActivityCover): void
    {
        $type = ActivityType::create(['name' => 'Curso', 'status' => 'activo']);
        $programCover = $this->cover('programa.jpg');
        $activityCover = $hasActivityCover ? $this->cover('actividad.jpg') : null;
        $program = TrainingProgram::create([
            'activity_type_id' => $type->id, 'name' => 'Curso con portada', 'slug' => 'curso-con-portada',
            'duration_hours' => 4, 'cover_file_id' => $programCover->id,
        ]);
        $activity = $program->activities()->create([
            'name' => $program->name, 'cover_file_id' => $activityCover?->id,
        ]);
        Enrollment::create([
            'user_id' => $this->student->id, 'activity_id' => $activity->id,
            'status' => 'aprobada', 'completion_status' => 'completado', 'final_score' => 95,
        ]);

        $response = $this->get(route('participant.profile'))->assertOk()
            ->assertSee('Portada de Curso con portada')->assertSee('95.00')
            ->assertSee(($activityCover ?? $programCover)->path, false)
            ->assertSee('Constancia pendiente de emisión.');

        if ($activityCover) {
            $response->assertDontSee($programCover->path, false);
        }
    }

    public function test_profile_totals_cover_all_pages_and_only_the_students_completed_training(): void
    {
        $type = ActivityType::create(['name' => 'Taller', 'status' => 'activo']);
        for ($index = 1; $index <= 13; $index++) {
            $activity = Activity::create([
                'activity_type_id' => $type->id, 'name' => 'Taller '.$index, 'slug' => 'taller-'.$index,
                'duration_hours' => 2.5,
            ]);
            $enrollment = Enrollment::create([
                'user_id' => $this->student->id, 'activity_id' => $activity->id,
                'status' => 'aprobada', 'completion_status' => 'completado', 'final_score' => 90,
            ]);
        }
        $certificateFile = FileUpload::create([
            'disk' => 'local', 'path' => 'certificates/prueba.pdf', 'original_name' => 'prueba.pdf',
            'stored_name' => 'prueba.pdf', 'mime_type' => 'application/pdf', 'extension' => 'pdf',
        ]);
        Certificate::create([
            'user_id' => $this->student->id, 'enrollment_id' => $enrollment->id, 'activity_id' => $activity->id,
            'file_upload_id' => $certificateFile->id, 'folio' => 'PERFIL-001', 'status' => 'emitida',
        ]);
        $pending = Activity::create([
            'activity_type_id' => $type->id, 'name' => 'Taller pendiente', 'slug' => 'taller-pendiente', 'duration_hours' => 100,
        ]);
        Enrollment::create(['user_id' => $this->student->id, 'activity_id' => $pending->id, 'status' => 'solicitada']);
        $other = User::factory()->create();
        Enrollment::create([
            'user_id' => $other->id, 'activity_id' => $pending->id,
            'status' => 'aprobada', 'completion_status' => 'completado',
        ]);
        Certificate::create([
            'user_id' => $other->id, 'file_upload_id' => $certificateFile->id, 'folio' => 'PERFIL-OTRO', 'status' => 'emitida',
        ]);

        $this->get(route('participant.profile'))->assertOk()
            ->assertViewHas('completedEnrollments', fn ($records): bool => $records->total() === 13 && $records->count() === 12)
            ->assertViewHas('completedHours', 32.5)->assertViewHas('availableCertificates', 1)
            ->assertSee('Descargar constancia')->assertSee('img/courses/course-general.png', false)
            ->assertDontSee('Taller pendiente');
    }

    public function test_a_student_without_completions_gets_a_working_catalog_link(): void
    {
        $this->get(route('participant.profile'))->assertOk()
            ->assertSee('Tu próximo logro empieza aquí')->assertSee(route('participant.catalog.index'), false)
            ->assertViewHas('completedHours', 0.0)->assertViewHas('availableCertificates', 0);
    }

    public static function coverSources(): array
    {
        return ['activity cover' => [true], 'program cover' => [false]];
    }

    private function cover(string $name): FileUpload
    {
        return FileUpload::create([
            'disk' => 'public', 'path' => 'course-covers/'.$name, 'stored_name' => $name,
            'original_name' => $name, 'mime_type' => 'image/jpeg', 'extension' => 'jpg', 'resource_type' => 'image',
        ]);
    }
}
