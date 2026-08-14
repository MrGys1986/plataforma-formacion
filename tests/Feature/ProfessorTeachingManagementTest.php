<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Enrollment;
use App\Models\Evidence;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProfessorTeachingManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_assigned_professor_can_approve_a_new_enrollment_request(): void
    {
        Role::findOrCreate('Profesor');
        Role::findOrCreate('Alumno');
        $professor = User::factory()->create(['status' => 'activo']);
        $professor->assignRole('Profesor');
        $participant = User::factory()->create(['name' => 'Nueva Alumna', 'status' => 'activo']);
        $participant->assignRole('Alumno');
        $type = ActivityType::create(['name' => 'Curso', 'status' => 'activo']);
        $activity = Activity::create([
            'activity_type_id' => $type->id,
            'instructor_id' => $professor->id,
            'name' => 'Curso con solicitudes',
            'slug' => 'curso-con-solicitudes',
            'duration_hours' => 4,
            'status' => 'publicado',
        ]);
        $enrollment = Enrollment::create([
            'user_id' => $participant->id,
            'activity_id' => $activity->id,
            'status' => 'solicitada',
            'requested_at' => now(),
        ]);

        $this->actingAs($professor)
            ->get(route('participant.professor.teaching.show', $activity))
            ->assertOk()
            ->assertSee('Solicitudes de inscripción')
            ->assertSee('Nueva Alumna')
            ->assertSee('Aprobar inscripción');

        $this->actingAs($professor)
            ->patch(route('participant.professor.teaching.enrollments.review', [$activity, $enrollment]), [
                'status' => 'aprobada',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollment->id,
            'status' => 'aprobada',
            'approved_by' => $professor->id,
        ]);
    }

    public function test_the_assigned_professor_can_search_grade_and_validate_a_participant(): void
    {
        Role::findOrCreate('Profesor');
        Role::findOrCreate('Alumno');
        $professor = User::factory()->create(['status' => 'activo']);
        $professor->assignRole('Profesor');
        $participant = User::factory()->create(['name' => 'Alumno Buscable', 'status' => 'activo']);
        $participant->assignRole('Alumno');
        $type = ActivityType::create(['name' => 'Curso', 'status' => 'activo']);
        $activity = Activity::create([
            'activity_type_id' => $type->id,
            'instructor_id' => $professor->id,
            'name' => 'Curso docente',
            'slug' => 'curso-docente',
            'duration_hours' => 4,
            'status' => 'publicado',
        ]);
        $enrollment = Enrollment::create([
            'user_id' => $participant->id,
            'activity_id' => $activity->id,
            'status' => 'aprobada',
        ]);
        $evidence = Evidence::create([
            'user_id' => $participant->id,
            'activity_id' => $activity->id,
            'enrollment_id' => $enrollment->id,
            'title' => 'Trabajo final',
            'evidence_type' => 'producto',
            'status' => 'pendiente',
        ]);

        $this->actingAs($professor)
            ->get(route('participant.professor.teaching.show', ['activity' => $activity, 'q' => 'Buscable']))
            ->assertOk()
            ->assertSee('Alumno Buscable')
            ->assertSee('Trabajo final')
            ->assertSee('Guardar evaluación')
            ->assertSee('Validar evidencia');

        $this->actingAs($professor)
            ->patch(route('participant.professor.teaching.enrollments.update', [$activity, $enrollment]), [
                'final_score' => 85,
                'completion_status' => 'completado',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollment->id,
            'final_score' => 85,
            'completion_status' => 'completado',
        ]);

        $this->actingAs($professor)
            ->post(route('participant.professor.teaching.evidences.review', [$activity, $evidence]), [
                'status' => 'validada',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('evidences', [
            'id' => $evidence->id,
            'status' => 'validada',
            'validated_by' => $professor->id,
        ]);
        $this->assertDatabaseHas('evidence_reviews', [
            'evidence_id' => $evidence->id,
            'reviewed_by' => $professor->id,
            'new_status' => 'validada',
        ]);
    }

    public function test_another_professor_cannot_manage_the_course_participants(): void
    {
        Role::findOrCreate('Profesor');
        $owner = User::factory()->create(['status' => 'activo']);
        $owner->assignRole('Profesor');
        $intruder = User::factory()->create(['status' => 'activo']);
        $intruder->assignRole('Profesor');
        $participant = User::factory()->create(['status' => 'activo']);
        $type = ActivityType::create(['name' => 'Taller', 'status' => 'activo']);
        $activity = Activity::create([
            'activity_type_id' => $type->id,
            'instructor_id' => $owner->id,
            'name' => 'Taller protegido',
            'slug' => 'taller-protegido',
            'duration_hours' => 2,
            'status' => 'publicado',
        ]);
        $enrollment = Enrollment::create([
            'user_id' => $participant->id,
            'activity_id' => $activity->id,
            'status' => 'aprobada',
        ]);

        $this->actingAs($intruder)
            ->patch(route('participant.professor.teaching.enrollments.update', [$activity, $enrollment]), [
                'final_score' => 100,
                'completion_status' => 'completado',
            ])
            ->assertForbidden();
    }
}
