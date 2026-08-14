<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProfessorCertificateUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_course_professor_can_upload_a_certificate_for_a_completed_participant(): void
    {
        Storage::fake('local');
        Role::findOrCreate('Profesor');
        Role::findOrCreate('Alumno');

        $professor = User::factory()->create(['status' => 'activo']);
        $professor->assignRole('Profesor');
        $participant = User::factory()->create(['status' => 'activo']);
        $participant->assignRole('Alumno');
        $type = ActivityType::create(['name' => 'Taller', 'status' => 'activo']);
        $activity = Activity::create([
            'activity_type_id' => $type->id,
            'instructor_id' => $professor->id,
            'name' => 'Taller completado',
            'slug' => 'taller-completado',
            'duration_hours' => 4,
            'status' => 'publicado',
        ]);
        $enrollment = Enrollment::create([
            'user_id' => $participant->id,
            'activity_id' => $activity->id,
            'status' => 'aprobada',
            'completion_status' => 'completado',
            'completed_at' => now(),
        ]);

        $this->actingAs($professor)->post(
            route('participant.professor.teaching.certificates.store', [$activity, $enrollment]),
            ['certificate' => UploadedFile::fake()->create('constancia.pdf', 100, 'application/pdf')],
        )->assertRedirect();

        $this->assertDatabaseHas('certificates', [
            'user_id' => $participant->id,
            'activity_id' => $activity->id,
            'enrollment_id' => $enrollment->id,
            'issued_by' => $professor->id,
            'status' => 'emitida',
        ]);

        $this->actingAs($participant)
            ->get(route('participant.certificates.index'))
            ->assertOk()
            ->assertSee('Taller completado')
            ->assertSee('Descargar constancia');
    }
}
