<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Area;
use App\Models\CertificationProgram;
use App\Models\DiplomaProgram;
use App\Models\LearningPath;
use App\Models\TrainingProgram;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Catálogo de ejemplo: no crea usuarios, asignaciones ni inscripciones.
 * Se puede ejecutar varias veces sin duplicar programas o relaciones.
 */
class LearningPathCatalogSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $areas = [
                'calidad' => Area::firstOrCreate(['name' => 'Calidad Académica'], ['area_type' => 'académica', 'status' => 'activa']),
                'continua' => Area::firstOrCreate(['name' => 'Educación Continua'], ['area_type' => 'administrativa', 'status' => 'activa']),
                'rh' => Area::firstOrCreate(['name' => 'Recursos Humanos'], ['area_type' => 'administrativa', 'status' => 'activa']),
            ];

            $types = collect(['Curso', 'Minicurso', 'Taller'])
                ->mapWithKeys(fn (string $name): array => [
                    $name => ActivityType::firstOrCreate(['name' => $name], ['status' => 'activo']),
                ]);

            $definitions = [
                'seguridad-operacional-aplicada-catalogo' => ['Curso', 'calidad', 12, 'Seguridad operacional aplicada', 'Aplica principios de gestión de seguridad operacional a escenarios de la operación aeronáutica.', 'Reconocer peligros, valorar riesgos y proponer controles efectivos.', true],
                'cultura-de-reporte-aeronautico-catalogo' => ['Minicurso', 'calidad', 4, 'Cultura de reporte aeronáutico', 'Introducción práctica al reporte oportuno y no punitivo de sucesos e incidentes.', 'Redactar reportes útiles para la prevención y la mejora continua.', false],
                'analisis-de-riesgos-aeronauticos-catalogo' => ['Taller', 'calidad', 8, 'Análisis de riesgos aeronáuticos', 'Taller de identificación de peligros, matrices de riesgo y selección de controles.', 'Construir una matriz de riesgos y justificar medidas de mitigación.', true],
                'diseno-instruccional-por-competencias-catalogo' => ['Curso', 'continua', 16, 'Diseño instruccional por competencias', 'Planeación de experiencias formativas alineadas con resultados de aprendizaje.', 'Diseñar una secuencia didáctica con evidencias y criterios de evaluación.', true],
                'herramientas-digitales-para-formacion-catalogo' => ['Minicurso', 'continua', 6, 'Herramientas digitales para la formación', 'Recursos digitales para organizar contenidos y acompañar el aprendizaje.', 'Seleccionar herramientas digitales según objetivos y modalidad.', false],
                'evaluacion-autentica-taller-catalogo' => ['Taller', 'continua', 8, 'Evaluación auténtica del aprendizaje', 'Diseño de rúbricas y actividades de evaluación basadas en situaciones reales.', 'Crear una rúbrica y una actividad de evaluación aplicable a un curso.', true],
                'sistemas-de-gestion-de-calidad-catalogo' => ['Curso', 'calidad', 12, 'Sistemas de gestión de calidad', 'Fundamentos de procesos, indicadores, trazabilidad y mejora continua.', 'Interpretar procesos e indicadores y plantear acciones de mejora.', true],
                'auditoria-interna-y-mejora-catalogo' => ['Taller', 'calidad', 8, 'Auditoría interna y acciones de mejora', 'Simulación de hallazgos, análisis de causas y seguimiento de acciones correctivas.', 'Documentar hallazgos y elaborar un plan de mejora verificable.', true],
                'induccion-institucional-recursos-humanos' => ['Curso', 'rh', 8, 'Inducción institucional para Recursos Humanos', 'Introducción a los procesos y servicios institucionales de Recursos Humanos.', 'Ubicar los procesos de atención y gestión del personal.', false],
                'proteccion-datos-personales-rh' => ['Minicurso', 'rh', 4, 'Protección de datos personales', 'Buenas prácticas para el tratamiento de información del personal.', 'Manejar datos personales conforme a los principios institucionales.', false],
                'prevencion-riesgos-psicosociales-rh' => ['Curso', 'rh', 10, 'Prevención de riesgos psicosociales', 'Factores de riesgo, prevención y canales de atención en el trabajo.', 'Reconocer señales de riesgo y promover entornos laborales saludables.', false],
                'atencion-comunicacion-personal-rh' => ['Minicurso', 'rh', 6, 'Atención y comunicación con el personal', 'Comunicación clara y atención respetuosa en situaciones cotidianas.', 'Aplicar técnicas de escucha y comunicación efectiva.', false],
            ];

            $programs = [];
            foreach ($definitions as $slug => [$type, $area, $hours, $name, $description, $objective, $evaluated]) {
                $programs[$slug] = TrainingProgram::firstOrCreate(['slug' => $slug], [
                    'activity_type_id' => $types[$type]->id,
                    'area_id' => $areas[$area]->id,
                    'name' => $name,
                    'description' => $description,
                    'general_objective' => $objective,
                    'default_modality' => $type === 'Taller' ? 'presencial' : 'virtual',
                    'duration_hours' => $hours,
                    'requires_approval' => false,
                    'requires_survey' => false,
                    'requires_evaluation' => $evaluated,
                    'generates_certificate' => true,
                    'generates_microcredential' => in_array($slug, [
                        'cultura-de-reporte-aeronautico-catalogo',
                        'diseno-instruccional-por-competencias-catalogo',
                    ], true),
                    'status' => 'activo',
                ]);
            }

            foreach ($programs as $program) {
                if (! $program->editions()->exists()) {
                    Activity::firstOrCreate(['slug' => $program->slug.'-edicion-catalogo'], [
                        'training_program_id' => $program->id,
                        'name' => $program->name.' - Edición de catálogo',
                        'activity_type_id' => $program->activity_type_id,
                        'duration_hours' => $program->duration_hours,
                        'status' => 'publicado',
                    ]);
                }
            }

            $diplomas = [
                'seguridad-operacional' => DiplomaProgram::firstOrCreate(['slug' => 'diplomado-seguridad-operacional-catalogo'], [
                    'area_id' => $areas['calidad']->id,
                    'name' => 'Diplomado en seguridad operacional',
                    'description' => 'Fundamentos, cultura de reporte y aplicación práctica del análisis de riesgos.',
                    'objective' => 'Integrar herramientas de prevención en la operación aeronáutica.',
                    'completion_criteria' => 'Completar los módulos obligatorios y aprobar las actividades evaluables con al menos 80 puntos.',
                    'total_hours' => 24,
                    'generates_microcredential' => true,
                    'status' => 'activo',
                ]),
                'docencia' => DiplomaProgram::firstOrCreate(['slug' => 'diplomado-docencia-innovadora-catalogo'], [
                    'area_id' => $areas['continua']->id,
                    'name' => 'Diplomado en docencia innovadora',
                    'description' => 'Diseño instruccional, herramientas digitales y evaluación auténtica.',
                    'objective' => 'Planear y evaluar experiencias de aprendizaje centradas en competencias.',
                    'completion_criteria' => 'Completar los tres módulos y aprobar curso y taller con al menos 80 puntos.',
                    'total_hours' => 30,
                    'generates_microcredential' => true,
                    'status' => 'activo',
                ]),
                'talento' => DiplomaProgram::firstOrCreate(['slug' => 'diplomado-gestion-del-talento-catalogo'], [
                    'area_id' => $areas['rh']->id,
                    'name' => 'Diplomado en gestión del talento',
                    'description' => 'Integra los programas existentes de inducción, protección de datos, bienestar y comunicación.',
                    'objective' => 'Aplicar prácticas responsables de atención y desarrollo del personal.',
                    'completion_criteria' => 'Completar los cuatro módulos del diplomado.',
                    'total_hours' => 28,
                    'generates_microcredential' => false,
                    'status' => 'activo',
                ]),
            ];

            $this->attach($diplomas['seguridad-operacional']->trainingPrograms(), $programs, [
                ['seguridad-operacional-aplicada-catalogo', true, 80],
                ['cultura-de-reporte-aeronautico-catalogo', true, null],
                ['analisis-de-riesgos-aeronauticos-catalogo', true, 80],
            ]);
            $this->attach($diplomas['docencia']->trainingPrograms(), $programs, [
                ['diseno-instruccional-por-competencias-catalogo', true, 80],
                ['herramientas-digitales-para-formacion-catalogo', true, null],
                ['evaluacion-autentica-taller-catalogo', true, 80],
            ]);
            $this->attach($diplomas['talento']->trainingPrograms(), $programs, [
                ['induccion-institucional-recursos-humanos', true, null],
                ['proteccion-datos-personales-rh', true, null],
                ['prevencion-riesgos-psicosociales-rh', true, null],
                ['atencion-comunicacion-personal-rh', true, null],
            ]);

            $certification = CertificationProgram::firstOrCreate(['slug' => 'certificacion-calidad-y-seguridad-catalogo'], [
                'area_id' => $areas['calidad']->id,
                'name' => 'Certificación en calidad y seguridad operacional',
                'description' => 'Trayecto avanzado que une un diplomado con formación aplicada en calidad y auditoría.',
                'objective' => 'Demostrar capacidad para gestionar riesgos y mejorar procesos.',
                'completion_criteria' => 'Completar el diplomado y aprobar el curso y taller con al menos 80 puntos.',
                'generates_microcredential' => true,
                'status' => 'activo',
            ]);
            $this->attach($certification->diplomaPrograms(), $diplomas, [['seguridad-operacional', true]]);
            $this->attach($certification->trainingPrograms(), $programs, [
                ['sistemas-de-gestion-de-calidad-catalogo', true, 80],
                ['auditoria-interna-y-mejora-catalogo', true, 80],
            ]);

            $routes = [
                'seguridad-basica' => LearningPath::firstOrCreate(['slug' => 'ruta-seguridad-operacional-basica-catalogo'], [
                    'area_id' => $areas['calidad']->id, 'name' => 'Ruta básica de seguridad operacional',
                    'description' => 'Tres actividades directas: curso, minicurso y taller con aplicación progresiva.',
                    'objective' => 'Identificar peligros, reportar sucesos y aplicar controles de riesgo.',
                    'target_audience' => 'Personal de operación y apoyo aeronáutico.',
                    'total_hours' => 24, 'is_sequential' => true, 'generates_diploma' => false,
                    'generates_microcredential' => true, 'status' => 'publicada',
                ]),
                'docencia' => LearningPath::firstOrCreate(['slug' => 'ruta-docencia-y-evaluacion-catalogo'], [
                    'area_id' => $areas['continua']->id, 'name' => 'Ruta de docencia y evaluación',
                    'description' => 'Diplomado completo más un minicurso opcional de protección de datos.',
                    'objective' => 'Diseñar formación centrada en competencias con evaluación y manejo responsable de información.',
                    'target_audience' => 'Docentes y diseñadores de formación.',
                    'total_hours' => 34, 'is_sequential' => false, 'generates_diploma' => false,
                    'generates_microcredential' => true, 'status' => 'publicada',
                ]),
                'certificacion' => LearningPath::firstOrCreate(['slug' => 'ruta-calidad-y-seguridad-integral-catalogo'], [
                    'area_id' => $areas['calidad']->id, 'name' => 'Ruta integral de calidad y seguridad',
                    'description' => 'Certificación que incluye un diplomado, curso y taller; añade un minicurso digital opcional.',
                    'objective' => 'Integrar seguridad operacional, calidad y mejora continua.',
                    'target_audience' => 'Responsables de calidad, seguridad y coordinación académica.',
                    'total_hours' => 50, 'is_sequential' => true, 'generates_diploma' => false,
                    'generates_microcredential' => false, 'status' => 'publicada',
                ]),
                'talento' => LearningPath::firstOrCreate(['slug' => 'ruta-gestion-de-personas-catalogo'], [
                    'area_id' => $areas['rh']->id, 'name' => 'Ruta de gestión de personas',
                    'description' => 'Diplomado construido con programas existentes de RH y un minicurso digital adicional.',
                    'objective' => 'Fortalecer la inducción, el bienestar y la comunicación con el personal.',
                    'target_audience' => 'Personal de Recursos Humanos y responsables de equipo.',
                    'total_hours' => 34, 'is_sequential' => false, 'generates_diploma' => false,
                    'generates_microcredential' => false, 'status' => 'publicada',
                ]),
            ];

            $this->attach($routes['seguridad-basica']->trainingPrograms(), $programs, [
                ['seguridad-operacional-aplicada-catalogo', true, 80],
                ['cultura-de-reporte-aeronautico-catalogo', true, null],
                ['analisis-de-riesgos-aeronauticos-catalogo', true, 80],
            ]);
            $this->attach($routes['docencia']->diplomaPrograms(), $diplomas, [['docencia', true]]);
            $this->attach($routes['docencia']->trainingPrograms(), $programs, [['proteccion-datos-personales-rh', false, null, 2]]);
            $this->attach($routes['certificacion']->certificationPrograms(), ['calidad' => $certification], [['calidad', true]]);
            $this->attach($routes['certificacion']->trainingPrograms(), $programs, [['herramientas-digitales-para-formacion-catalogo', false, null, 2]]);
            $this->attach($routes['talento']->diplomaPrograms(), $diplomas, [['talento', true]]);
            $this->attach($routes['talento']->trainingPrograms(), $programs, [['herramientas-digitales-para-formacion-catalogo', false, null, 2]]);

            $programCompetencies = [
                'seguridad-operacional-aplicada-catalogo' => 'Identifica peligros operacionales y propone controles de riesgo.',
                'cultura-de-reporte-aeronautico-catalogo' => 'Comunica sucesos e incidentes de forma clara y oportuna.',
                'analisis-de-riesgos-aeronauticos-catalogo' => 'Construye matrices de riesgo y justifica medidas de mitigación.',
                'diseno-instruccional-por-competencias-catalogo' => 'Diseña secuencias didácticas alineadas con resultados de aprendizaje.',
                'herramientas-digitales-para-formacion-catalogo' => 'Selecciona y utiliza herramientas digitales para facilitar el aprendizaje.',
                'evaluacion-autentica-taller-catalogo' => 'Diseña rúbricas y evalúa desempeños en situaciones reales.',
                'sistemas-de-gestion-de-calidad-catalogo' => 'Interpreta procesos e indicadores para impulsar la mejora continua.',
                'auditoria-interna-y-mejora-catalogo' => 'Documenta hallazgos y da seguimiento a acciones correctivas.',
                'induccion-institucional-recursos-humanos' => 'Reconoce los procesos institucionales de atención y gestión del personal.',
                'proteccion-datos-personales-rh' => 'Protege los datos personales durante su tratamiento institucional.',
                'prevencion-riesgos-psicosociales-rh' => 'Identifica factores de riesgo psicosocial y promueve acciones preventivas.',
                'atencion-comunicacion-personal-rh' => 'Se comunica con el personal de manera clara, respetuosa y efectiva.',
            ];

            foreach ($programs as $slug => $program) {
                $this->fillCompetencies($program, $programCompetencies[$slug]);
            }
            foreach (['seguridad-operacional' => 'Gestiona peligros y riesgos para fortalecer la seguridad operacional.',
                'docencia' => 'Diseña, facilita y evalúa experiencias de aprendizaje por competencias.',
                'talento' => 'Aplica prácticas de inducción, bienestar y comunicación con el personal.'] as $key => $text) {
                $this->fillCompetencies($diplomas[$key], $text);
            }
            $this->fillCompetencies($certification, 'Integra seguridad operacional, auditoría y mejora de procesos.');
            foreach (['seguridad-basica' => 'Identifica, reporta y mitiga riesgos operacionales.',
                'docencia' => 'Diseña formación por competencias y evalúa su aprendizaje.',
                'certificacion' => 'Gestiona la seguridad y la calidad mediante evidencia y mejora continua.',
                'talento' => 'Atiende y desarrolla al personal con prácticas institucionales responsables.'] as $key => $text) {
                $this->fillCompetencies($routes[$key], $text);
            }
        });
    }

    private function fillCompetencies(Model $model, string $text): void
    {
        if (blank($model->competencies)) {
            $model->update(['competencies' => $text]);
        }
    }

    /** @param array<string, object> $models */
    private function attach(BelongsToMany $relation, array $models, array $entries): void
    {
        foreach ($entries as $index => $entry) {
            [$key, $required] = $entry;
            $minimumScore = $entry[2] ?? null;
            $model = $models[$key];
            if (DB::table($relation->getTable())
                ->where($relation->getForeignPivotKeyName(), $relation->getParent()->getKey())
                ->where($relation->getRelatedPivotKeyName(), $model->id)
                ->exists()) {
                continue;
            }

            $pivot = ['order_number' => $entry[3] ?? $index + 1, 'is_required' => $required];
            if ($relation->getRelated() instanceof TrainingProgram) {
                $pivot['minimum_score'] = $minimumScore;
            }
            $relation->attach($model->id, $pivot);
        }
    }
}
