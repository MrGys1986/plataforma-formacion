<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Enrollment;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SurveyPublicIdRepairTest extends TestCase
{
    use RefreshDatabase;

    public function test_legacy_surveys_and_questions_can_be_opened_and_answered_after_repair(): void
    {
        Role::findOrCreate('Alumno');
        $student = User::factory()->create(['status' => 'activo']);
        $student->assignRole('Alumno');
        $type = ActivityType::create(['name' => 'Curso', 'status' => 'activo']);
        $activity = Activity::create([
            'activity_type_id' => $type->id, 'name' => 'Curso completado',
            'slug' => 'curso-completado', 'duration_hours' => 4, 'status' => 'publicado',
        ]);
        $enrollment = Enrollment::create([
            'activity_id' => $activity->id, 'user_id' => $student->id,
            'status' => 'aprobada', 'completion_status' => 'completado', 'final_score' => 95,
        ]);
        $surveyId = DB::table('surveys')->insertGetId([
            'name' => 'Encuesta antigua', 'is_general' => true, 'status' => 'activa', 'public_id' => null,
        ]);
        DB::table('survey_questions')->insert([
            ['survey_id' => $surveyId, 'question_text' => 'Calidad del contenido', 'question_type' => 'escala', 'is_required' => true, 'public_id' => null],
            ['survey_id' => $surveyId, 'question_text' => 'Comentarios', 'question_type' => 'texto', 'is_required' => false, 'public_id' => ''],
        ]);
        $existing = Survey::create(['name' => 'Encuesta con identificador', 'status' => 'activa']);
        $existingPublicId = $existing->public_id;

        $migration = require database_path('migrations/2026_10_13_000000_backfill_missing_survey_public_ids.php');
        $migration->up();
        $survey = Survey::with('questions')->findOrFail($surveyId);
        $this->assertTrue(Str::isUlid($survey->public_id));

        foreach ($survey->questions as $question) {
            $this->assertTrue(Str::isUlid($question->public_id));
        }

        $this->assertSame($existingPublicId, $existing->fresh()->public_id);
        $this->actingAs($student)->get(route('participant.learning.show', $enrollment))
            ->assertOk()->assertSee(route('participant.surveys.show', ['survey' => $survey, 'activity' => $activity]), false);
        $this->get(route('participant.surveys.show', ['survey' => $survey, 'activity' => $activity]))
            ->assertOk()->assertSee('Calidad del contenido')->assertSee('Enviar evaluación del curso');
        $this->post(route('participant.surveys.store', ['survey' => $survey, 'activity' => $activity]), [
            'answers' => [['question_public_id' => $survey->questions->first()->public_id, 'value' => '5']],
        ])->assertSessionHasNoErrors()->assertRedirectToRoute('participant.learning.show', $enrollment);
        $answersBeforeRepair = DB::table('survey_responses')->sole();
        $idsBeforeRepair = $survey->questions->pluck('public_id', 'id')->all();

        $migration->up();

        $this->assertSame($survey->public_id, $survey->fresh()->public_id);
        $this->assertSame($idsBeforeRepair, $survey->questions()->pluck('public_id', 'id')->all());
        $this->assertEquals($answersBeforeRepair, DB::table('survey_responses')->sole());
        $this->get(route('participant.learning.show', $enrollment))->assertOk()->assertSee('Encuesta respondida:');
    }
}
