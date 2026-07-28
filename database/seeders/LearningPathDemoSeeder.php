<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Area;
use App\Models\Enrollment;
use App\Models\LearningPath;
use App\Models\Microcredential;
use App\Models\User;
use App\Models\UserLearningPath;
use App\Services\LearningPaths\LearningPathProgressService;
use App\Services\Microcredentials\MicrocredentialPayloadService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class LearningPathDemoSeeder extends Seeder
{
    public function run(): void
    {
        $area = Area::query()->where('name', 'Calidad Académica')->first()
            ?? Area::query()->firstOrCreate(
                ['name' => 'Calidad Académica'],
                ['area_type' => 'académica', 'status' => 'activa'],
            );

        $activityType = ActivityType::query()->where('name', 'Curso')->first()
            ?? ActivityType::query()->create(['name' => 'Curso', 'status' => 'activo']);

        $participant = User::query()->firstOrCreate(
            ['email' => 'profesor.demo@universidad.test'],
            [
                'name' => 'Profesor Demostración',
                'password' => Hash::make('password'),
                'user_type' => 'interno',
                'profile_type' => 'profesor',
                'area_id' => $area->id,
                'status' => 'activo',
            ],
        );

        Role::findOrCreate('Profesor');
        $participant->syncRoles(['Profesor']);

        $teachingProfessor = User::query()->updateOrCreate(
            ['email' => 'profesor@prueba.mx'],
            [
                'name' => 'Profesor de prueba',
                'password' => Hash::make('12345678'),
                'user_type' => 'interno',
                'profile_type' => 'profesor',
                'area_id' => $area->id,
                'status' => 'activo',
            ],
        );
        $teachingProfessor->syncRoles(['Profesor']);

        $teachingCourse = $this->createTeachingDemo($teachingProfessor, $activityType, $area);
        $this->createBadgeDemo($teachingProfessor, $teachingCourse);

        $activities = collect([
            ['slug' => 'induccion-institucional-demo', 'name' => 'Inducción institucional', 'hours' => 4],
            ['slug' => 'herramientas-digitales-docentes-demo', 'name' => 'Herramientas digitales para docentes', 'hours' => 8],
            ['slug' => 'evaluacion-del-aprendizaje-demo', 'name' => 'Evaluación del aprendizaje', 'hours' => 10],
            ['slug' => 'diseno-de-experiencias-demo', 'name' => 'Diseño de experiencias de aprendizaje', 'hours' => 12],
            ['slug' => 'proteccion-de-datos-demo', 'name' => 'Protección de datos personales', 'hours' => 5],
            ['slug' => 'liderazgo-academico-demo', 'name' => 'Liderazgo académico', 'hours' => 10],
        ])->mapWithKeys(function (array $definition) use ($activityType, $area): array {
            $activity = Activity::query()->updateOrCreate(
                ['slug' => $definition['slug']],
                [
                    'activity_type_id' => $activityType->id,
                    'area_id' => $area->id,
                    'name' => $definition['name'],
                    'description' => 'Actividad de demostración para visualizar el funcionamiento de las rutas de aprendizaje.',
                    'duration_hours' => $definition['hours'],
                    'modality' => 'virtual',
                    'requires_approval' => false,
                    'requires_payment' => false,
                    'requires_evaluation' => true,
                    'requires_survey' => false,
                    'generates_certificate' => true,
                    'status' => 'publicado',
                ],
            );

            return [$definition['slug'] => $activity];
        });

        $routes = [
            [
                'slug' => 'formacion-docente-inicial-demo',
                'name' => 'Formación docente inicial',
                'description' => 'Ruta introductoria para conocer la institución y fortalecer la práctica docente.',
                'objective' => 'Integrar al personal docente y brindarle herramientas básicas para planear, impartir y evaluar sus cursos.',
                'target_audience' => 'Profesoras y profesores de nuevo ingreso.',
                'is_sequential' => true,
                'items' => [
                    ['induccion-institucional-demo', true, 80],
                    ['herramientas-digitales-docentes-demo', true, 80],
                    ['evaluacion-del-aprendizaje-demo', true, 80],
                ],
            ],
            [
                'slug' => 'innovacion-educativa-demo',
                'name' => 'Innovación educativa',
                'description' => 'Recorrido flexible para incorporar tecnología y diseño pedagógico a las clases.',
                'objective' => 'Diseñar experiencias de aprendizaje activas apoyadas por herramientas digitales.',
                'target_audience' => 'Personal docente interesado en innovación educativa.',
                'is_sequential' => false,
                'items' => [
                    ['herramientas-digitales-docentes-demo', true, 75],
                    ['diseno-de-experiencias-demo', true, 75],
                    ['evaluacion-del-aprendizaje-demo', false, null],
                ],
            ],
            [
                'slug' => 'liderazgo-y-cumplimiento-demo',
                'name' => 'Liderazgo y cumplimiento institucional',
                'description' => 'Ruta para responsables académicos que combina liderazgo y manejo responsable de información.',
                'objective' => 'Fortalecer la toma de decisiones y el cumplimiento institucional.',
                'target_audience' => 'Coordinaciones y responsables de área.',
                'is_sequential' => true,
                'items' => [
                    ['proteccion-de-datos-demo', true, 80],
                    ['liderazgo-academico-demo', true, 80],
                ],
            ],
        ];

        foreach ($routes as $routeDefinition) {
            $path = LearningPath::query()->updateOrCreate(
                ['slug' => $routeDefinition['slug']],
                [
                    'area_id' => $area->id,
                    'name' => $routeDefinition['name'],
                    'description' => $routeDefinition['description'],
                    'objective' => $routeDefinition['objective'],
                    'target_audience' => $routeDefinition['target_audience'],
                    'total_hours' => collect($routeDefinition['items'])
                        ->sum(fn (array $item) => (float) $activities[$item[0]]->duration_hours),
                    'is_sequential' => $routeDefinition['is_sequential'],
                    'generates_diploma' => true,
                    'generates_microcredential' => false,
                    'status' => 'publicada',
                ],
            );

            foreach ($routeDefinition['items'] as $index => [$slug, $required, $minimumScore]) {
                $path->items()->updateOrCreate(
                    ['activity_id' => $activities[$slug]->id],
                    [
                        'order_number' => $index + 1,
                        'is_required' => $required,
                        'minimum_score' => $minimumScore,
                    ],
                );
            }

            UserLearningPath::query()->firstOrCreate([
                'user_id' => $participant->id,
                'learning_path_id' => $path->id,
            ]);
        }

        $completedActivity = $activities['induccion-institucional-demo'];
        $participant->enrollments()
            ->where('activity_id', $completedActivity->id)
            ->update([
                'completion_status' => 'completado',
                'final_score' => 95,
                'completed_at' => now(),
            ]);

        $participant->userLearningPaths()
            ->each(fn (UserLearningPath $assignment) => app(LearningPathProgressService::class)
                ->synchronizeAssignment($assignment));
    }

    private function createTeachingDemo(User $professor, ActivityType $activityType, Area $area): Activity
    {
        $course = Activity::query()->updateOrCreate(
            ['slug' => 'estrategias-de-ensenanza-activa-demo'],
            [
                'activity_type_id' => $activityType->id,
                'area_id' => $area->id,
                'instructor_id' => $professor->id,
                'name' => 'Estrategias de enseñanza activa - Edición demostración',
                'description' => 'Curso impartido por el profesor de prueba para mostrar el seguimiento docente.',
                'general_objective' => 'Aplicar estrategias activas que promuevan la participación del alumnado.',
                'modality' => 'virtual',
                'start_date' => now()->startOfWeek()->toDateString(),
                'end_date' => now()->addWeeks(4)->toDateString(),
                'schedule' => 'Martes y jueves, 17:00 a 19:00',
                'duration_hours' => 16,
                'min_capacity' => 5,
                'max_capacity' => 25,
                'cost' => 0,
                'requires_approval' => false,
                'requires_payment' => false,
                'requires_evaluation' => true,
                'requires_survey' => true,
                'generates_certificate' => true,
                'generates_microcredential' => true,
                'edition_number' => 1,
                'edition_code' => 'DOC-ACT-E01',
                'status' => 'en_curso',
            ],
        );

        Role::findOrCreate('Alumno');

        $students = collect([
            ['name' => 'Ana Martínez López', 'email' => 'ana.alumna@universidad.test'],
            ['name' => 'Carlos Hernández Ruiz', 'email' => 'carlos.alumno@universidad.test'],
            ['name' => 'Mariana Torres García', 'email' => 'mariana.alumna@universidad.test'],
        ])->map(function (array $data) use ($area): User {
            $student = User::query()->firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'user_type' => 'interno',
                    'profile_type' => 'alumno',
                    'area_id' => $area->id,
                    'status' => 'activo',
                ],
            );
            $student->syncRoles(['Alumno']);

            return $student;
        });

        foreach ($students as $index => $student) {
            Enrollment::query()->updateOrCreate(
                [
                    'user_id' => $student->id,
                    'activity_id' => $course->id,
                ],
                [
                    'status' => 'aprobada',
                    'requested_at' => now()->subDays(10 - $index),
                    'approved_at' => now()->subDays(8 - $index),
                    'approved_by' => $professor->id,
                    'payment_status' => 'no_aplica',
                    'completion_status' => 'no_iniciado',
                ],
            );
        }

        return $course;
    }

    private function createBadgeDemo(User $professor, Activity $course): void
    {
        $badge = Microcredential::query()->updateOrCreate(
            [
                'user_id' => $professor->id,
                'activity_id' => $course->id,
                'name' => 'Docencia innovadora',
            ],
            [
                'description' => 'Reconoce la aplicación de estrategias activas, el acompañamiento del alumnado y el uso de recursos digitales en la práctica docente.',
                'skills' => 'Planeación didáctica, aprendizaje activo y acompañamiento académico.',
                'competencies' => 'Diseña experiencias centradas en el estudiante y facilita ambientes participativos de aprendizaje.',
                'status' => 'validada',
                'issued_at' => now()->subDays(2),
            ],
        );

        $badge->update([
            'json_payload' => app(MicrocredentialPayloadService::class)
                ->buildPayload($badge->load('user')),
        ]);
    }
}
