<?php

namespace Database\Seeders;

use App\Models\ActivityType;
use App\Models\Survey;
use App\Models\TrainingProgram;
use App\Models\User;
use App\Services\Training\TrainingProgramOperationService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StandaloneTrainingDemoSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            foreach (['Alumno', 'Profesor'] as $role) {
                Role::findOrCreate($role, 'web');
            }

            $student = User::firstOrCreate(['email' => 'alumno.flujo@formacion.test'], [
                'name' => 'Alumno del flujo independiente',
                'password' => Hash::make(DemoRoleUserSeeder::DEFAULT_PASSWORD),
                'status' => 'activo', 'user_type' => 'interno', 'profile_type' => 'alumno',
            ]);
            $student->assignRole('Alumno');
            $professor = User::firstOrCreate(['email' => 'profesor.flujo@formacion.test'], [
                'name' => 'Profesor del flujo independiente',
                'password' => Hash::make(DemoRoleUserSeeder::DEFAULT_PASSWORD),
                'status' => 'activo', 'user_type' => 'interno', 'profile_type' => 'profesor',
            ]);
            $professor->assignRole('Profesor');

            foreach ([
                ['Curso', 'prueba-independiente-curso', 'Curso de prueba: Herramientas digitales', 8],
                ['Minicurso', 'prueba-independiente-minicurso', 'Minicurso de prueba: Organización del estudio', 2],
                ['Taller', 'prueba-independiente-taller', 'Taller de prueba: Trabajo en equipo', 4],
            ] as [$type, $slug, $name, $hours]) {
                $activityType = ActivityType::firstOrCreate(['name' => $type], ['status' => 'activo']);
                $program = TrainingProgram::firstOrCreate(['slug' => $slug], [
                    'activity_type_id' => $activityType->id, 'name' => $name,
                    'description' => 'Formación independiente de prueba para solicitar inscripción, recibir aprobación, registrar calificación, responder la encuesta y descargar la constancia.',
                    'duration_hours' => $hours, 'default_modality' => 'virtual',
                    'default_cost' => 0, 'status' => 'activo',
                    'requires_approval' => true, 'requires_payment' => false,
                    'requires_evaluation' => true, 'requires_survey' => true,
                    'generates_certificate' => true,
                ]);

                if (! $program->activities()->exists()) {
                    $activity = app(TrainingProgramOperationService::class)->resolve($program);
                    $activity->update(['instructor_id' => $professor->id, 'status' => 'publicado']);
                }
            }

            $survey = Survey::firstOrCreate(['is_general' => true], [
                'name' => 'Encuesta general de satisfacción', 'status' => 'activa',
            ]);

            if (! $survey->questions()->exists()) {
                foreach ([
                    ['¿El contenido fue útil para tu formación?', 'escala', true],
                    ['¿El profesor explicó los temas con claridad?', 'escala', true],
                    ['Comentarios sobre la formación', 'texto', false],
                ] as $index => [$text, $type, $required]) {
                    $survey->questions()->create([
                        'question_text' => $text, 'question_type' => $type,
                        'is_required' => $required, 'order_number' => $index + 1,
                    ]);
                }
            }
        });
    }
}
