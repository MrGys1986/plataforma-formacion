<?php

namespace App\Http\Controllers\Rh;

use App\Http\Controllers\Controller;
use App\Models\ActivityType;
use App\Models\Area;
use App\Models\TrainingProgram;
use Illuminate\Http\Request;

class CompetencyController extends Controller
{
    public function index(Request $request)
    {
        $this->ensureRequiredCoursesExist($request);

        $programs = TrainingProgram::query()
            ->visibleTo($request->user())
            ->where('status', 'activo')
            ->whereHas('area', fn ($area) => $area->where('name', 'Recursos Humanos'))
            ->with(['activityType', 'area'])
            ->withCount([
                'editions',
                'enrollments',
                'enrollments as completed_enrollments_count' => fn ($query) => $query->where('completion_status', 'completado'),
            ])
            ->orderBy('name')
            ->paginate(12);

        return view('rh.competencies.index', compact('programs'));
    }

    private function ensureRequiredCoursesExist(Request $request): void
    {
        $area = Area::query()->firstOrCreate(
            ['name' => 'Recursos Humanos'],
            ['description' => 'Área responsable de la gestión y desarrollo del personal.', 'area_type' => 'administrativa', 'status' => 'activa'],
        );

        $courseType = ActivityType::query()->firstOrCreate(
            ['name' => 'Curso'],
            ['description' => 'Programa de formación institucional.', 'default_generates_certificate' => true, 'status' => 'activo'],
        );
        $miniCourseType = ActivityType::query()->firstOrCreate(
            ['name' => 'Minicurso'],
            ['description' => 'Capacitación breve enfocada en un tema específico.', 'default_generates_certificate' => true, 'status' => 'activo'],
        );

        $courses = [
            [
                'name' => 'Inducción institucional para Recursos Humanos',
                'slug' => 'induccion-institucional-recursos-humanos',
                'activity_type_id' => $courseType->id,
                'description' => 'Conoce la estructura, políticas, responsabilidades y procesos esenciales de la institución.',
                'general_objective' => 'Integrar al personal de Recursos Humanos a la operación institucional.',
                'duration_hours' => 8,
                'default_modality' => 'virtual',
            ],
            [
                'name' => 'Protección de datos personales',
                'slug' => 'proteccion-datos-personales-rh',
                'activity_type_id' => $miniCourseType->id,
                'description' => 'Buenas prácticas para el tratamiento seguro y confidencial de expedientes del personal.',
                'general_objective' => 'Aplicar los principios de privacidad y protección de datos en los procesos de RH.',
                'duration_hours' => 4,
                'default_modality' => 'virtual',
            ],
            [
                'name' => 'Prevención de riesgos psicosociales',
                'slug' => 'prevencion-riesgos-psicosociales-rh',
                'activity_type_id' => $courseType->id,
                'description' => 'Identificación, prevención y atención de factores de riesgo psicosocial en el trabajo.',
                'general_objective' => 'Promover entornos organizacionales seguros y saludables.',
                'duration_hours' => 10,
                'default_modality' => 'híbrida',
            ],
            [
                'name' => 'Atención y comunicación con el personal',
                'slug' => 'atencion-comunicacion-personal-rh',
                'activity_type_id' => $miniCourseType->id,
                'description' => 'Herramientas para brindar orientación clara, empática y profesional al personal.',
                'general_objective' => 'Fortalecer la calidad del servicio interno de Recursos Humanos.',
                'duration_hours' => 6,
                'default_modality' => 'presencial',
            ],
        ];

        foreach ($courses as $course) {
            TrainingProgram::query()->updateOrCreate(
                ['slug' => $course['slug']],
                $course + [
                    'area_id' => $area->id,
                    'created_by' => $request->user()->id,
                    'language' => 'Español',
                    'default_cost' => 0,
                    'is_external' => false,
                    'requires_approval' => false,
                    'requires_payment' => false,
                    'requires_evaluation' => true,
                    'requires_survey' => true,
                    'generates_certificate' => true,
                    'generates_microcredential' => false,
                    'approval_criteria' => 'Completar el contenido y obtener una calificación mínima de 70 puntos.',
                    'status' => 'activo',
                ],
            );
        }
    }
}
