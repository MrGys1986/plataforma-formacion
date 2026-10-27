<?php

namespace App\Support;

use App\Models\User;

final class PortalConfiguration
{
    /**
     * @return array<string, mixed>
     */
    public static function forUser(?User $user): array
    {
        if (! $user) {
            return [
                'key' => 'public',
                'role' => 'Visitante',
                'name' => 'Plataforma Institucional de Formación y Microcredenciales',
                'title' => 'Plataforma Institucional de Formación y Microcredenciales',
                'description' => 'Formación institucional en un solo lugar.',
                'navigation' => [],
                'actions' => [],
            ];
        }

        return match (true) {
            $user->hasRole('Superadministrador') => self::administrator(),
            $user->hasRole('Profesor') => self::professor(),
            $user->hasRole('Alumno') => self::student(),
            $user->hasRole('Personal') => self::staff(),
            $user->hasRole('Responsable Area') => self::areaManager(),
            $user->hasRole('Evaluador') => self::evaluator(),
            $user->hasRole('Recursos Humanos') => self::humanResources(),
            $user->hasRole('Calidad Academica') => self::quality(),
            $user->hasRole('Educacion Continua') => self::continuingEducation(),
            $user->hasRole('Externo') || $user->user_type === 'externo' => self::external(),
            default => self::unsupported($user),
        };
    }

    /** @return array<string, mixed> */
    private static function administrator(): array
    {
        return self::portal(
            key: 'administrator',
            role: 'Superadministrador',
            name: 'Portal de administración',
            title: 'Panel institucional',
            description: 'Control central de usuarios, formación, seguimiento y operación de la plataforma.',
            navigation: [
                self::dashboardSectionItem('Inicio', null, 'home'),
                self::dashboardSectionItem('Usuarios', 'usuarios', 'users'),
                self::dashboardSectionItem('Formación', 'programas', 'catalog'),
                self::dashboardSectionItem('Pagos', 'pagos', 'payment'),
                self::dashboardSectionItem('Reportes', 'reportes', 'report'),
            ],
            actions: [
                self::dashboardAction('Usuarios', 'Administra cuentas, roles y áreas institucionales.', 'usuarios', 'users'),
                self::dashboardAction('Programas de formación', 'Consulta cursos, minicursos y talleres.', 'programas', 'catalog'),
                self::dashboardAction('Actividades', 'Revisa ediciones, fechas y operación académica.', 'actividades', 'courses'),
                self::dashboardAction('Inscripciones', 'Consulta solicitudes y participación registrada.', 'inscripciones', 'evaluation'),
                self::dashboardAction('Pagos', 'Revisa comprobantes y estados de validación.', 'pagos', 'payment'),
                self::dashboardAction('Reportes', 'Accede a los indicadores institucionales.', 'reportes', 'report'),
            ],
        );
    }

    /** @return array<string, mixed> */
    private static function professor(): array
    {
        return self::portal(
            key: 'professor',
            role: 'Profesor',
            name: 'Portal del profesor',
            title: 'Panel del profesor',
            description: 'Consulta tus cursos asignados y acompaña el avance de tus participantes.',
            navigation: [
                self::dashboardItem(),
                self::item('Catálogo de cursos', 'participant.catalog.index', 'catalog', 'participant.catalog.*'),
                self::item('Mi formación', 'participant.my-courses.index', 'courses', ['participant.my-courses.*', 'participant.learning.*']),
                self::section('Actividad docente'),
                self::item('Cursos que imparto', 'participant.professor.teaching.index', 'teaching', 'participant.professor.teaching.*'),
                self::section('Más recursos'),
                self::item('Rutas de aprendizaje', 'participant.learning-paths.index', 'path', 'participant.learning-paths.*'),
                self::item('Webinars', 'participant.webinars.index', 'video', 'participant.webinars.*'),
                self::item('Recursos digitales', 'participant.resources.index', 'catalog', 'participant.resources.*'),
            ],
            actions: [
                self::action('Cursos que imparto', 'Gestiona grupos, inscripciones, evidencias y resultados.', 'participant.professor.teaching.index', 'teaching'),
                self::action('Mi formación', 'Continúa los cursos en los que participas como estudiante.', 'participant.my-courses.index', 'courses'),
                self::action('Catálogo de cursos', 'Descubre nuevas oportunidades de formación.', 'participant.catalog.index', 'catalog'),
                self::action('Perfil docente', 'Consulta tu información y reconocimientos.', 'participant.professor.profile', 'certificate'),
            ],
            profileRoute: 'participant.professor.profile',
            profileLabel: 'Ver perfil docente',
        );
    }

    /** @return array<string, mixed> */
    private static function student(): array
    {
        return self::portal(
            key: 'student',
            role: 'Alumno',
            name: 'Portal del alumno',
            title: 'Bienvenido',
            description: 'Consulta tu formación, evidencias, evaluaciones y constancias desde un solo lugar.',
            navigation: self::participantNavigation(),
            actions: [
                self::action('Mi formación', 'Revisa tus cursos, avance y actividades pendientes.', 'participant.my-courses.index', 'courses'),
                self::action('Catálogo de cursos', 'Encuentra nuevas opciones de formación.', 'participant.catalog.index', 'catalog'),
                self::action('Rutas de aprendizaje', 'Avanza por itinerarios formativos organizados.', 'participant.learning-paths.index', 'path'),
                self::action('Mis constancias', 'Consulta los documentos que has obtenido.', 'participant.certificates.index', 'certificate'),
            ],
            profileRoute: 'participant.profile',
            profileLabel: 'Mi perfil',
        );
    }

    /** @return array<string, mixed> */
    private static function external(): array
    {
        $navigation = self::participantNavigation(includePayments: true);

        return self::portal(
            key: 'external',
            role: 'Participante externo',
            name: 'Portal del participante externo',
            title: 'Bienvenido',
            description: 'Consulta tu oferta de formación, inscripciones, pagos y constancias.',
            navigation: $navigation,
            actions: [
                self::action('Explorar oferta', 'Consulta cursos, talleres y actividades disponibles.', 'participant.catalog.index', 'catalog'),
                self::action('Mi formación', 'Revisa tus inscripciones, avance y entregas.', 'participant.my-courses.index', 'courses'),
                self::action('Mis pagos', 'Consulta comprobantes y estados de validación.', 'participant.payments.index', 'payment'),
                self::action('Mis constancias', 'Accede a tus documentos emitidos.', 'participant.certificates.index', 'certificate'),
            ],
            profileRoute: 'participant.profile',
            profileLabel: 'Mi perfil',
        );
    }

    /** @return array<int, array<string, mixed>> */
    private static function participantNavigation(bool $includePayments = false): array
    {
        $items = [
            self::dashboardItem(),
            self::item('Catálogo de cursos', 'participant.catalog.index', 'catalog', 'participant.catalog.*'),
            self::item('Mi formación', 'participant.my-courses.index', 'courses', ['participant.my-courses.*', 'participant.learning.*']),
        ];

        if ($includePayments) {
            $items[] = self::item('Mis pagos', 'participant.payments.index', 'payment', 'participant.payments.*');
        }

        return [
            ...$items,
            self::section('Más recursos'),
            self::item('Rutas de aprendizaje', 'participant.learning-paths.index', 'path', 'participant.learning-paths.*'),
            self::item('Mis constancias', 'participant.certificates.index', 'certificate', 'participant.certificates.*'),
            self::item('Webinars', 'participant.webinars.index', 'video', 'participant.webinars.*'),
            self::item('Recursos digitales', 'participant.resources.index', 'catalog', 'participant.resources.*'),
        ];
    }

    /** @return array<string, mixed> */
    private static function staff(): array
    {
        return self::portal(
            key: 'staff', role: 'Personal', name: 'Portal del personal', title: 'Panel del personal',
            description: 'Consulta y administra las actividades que tienes asignadas dentro de la universidad.',
            navigation: [
                self::dashboardItem(),
                self::item('Mis actividades', 'personal.courses.index', 'courses', ['personal.courses.*', 'personal.attendance.*', 'personal.evidences.*', 'personal.evaluations.*', 'personal.certificates.*']),
            ],
            actions: [
                self::action('Mis actividades', 'Consulta cursos, participantes, asistencia y entregas.', 'personal.courses.index', 'courses'),
            ],
        );
    }

    /** @return array<string, mixed> */
    private static function evaluator(): array
    {
        return self::portal(
            key: 'evaluator', role: 'Evaluador', name: 'Portal del evaluador', title: 'Panel del evaluador',
            description: 'Revisa evidencias, resultados y procesos de certificación.',
            navigation: [
                self::dashboardItem(),
                self::item('Evidencias', 'evaluator.evidences.index', 'evidence', 'evaluator.evidences.*'),
                self::item('Rúbricas', 'evaluator.rubrics.index', 'evaluation', 'evaluator.rubrics.*'),
                self::item('Evaluaciones', 'evaluator.evaluations.index', 'evaluation', 'evaluator.evaluations.*'),
                self::item('Certificaciones', 'evaluator.certifications.index', 'certificate', 'evaluator.certifications.*'),
            ],
            actions: [
                self::action('Evidencias', 'Abre tu bandeja de documentos asignados.', 'evaluator.evidences.index', 'evidence'),
                self::action('Evaluaciones', 'Consulta los resultados que has registrado.', 'evaluator.evaluations.index', 'evaluation'),
                self::action('Rúbricas', 'Consulta los criterios institucionales.', 'evaluator.rubrics.index', 'report'),
                self::action('Certificaciones', 'Revisa los resultados de certificación.', 'evaluator.certifications.index', 'certificate'),
            ],
        );
    }

    /** @return array<string, mixed> */
    private static function humanResources(): array
    {
        return self::portal(
            key: 'human-resources', role: 'Recursos Humanos', name: 'Portal de Recursos Humanos', title: 'Panel de Recursos Humanos',
            description: 'Seguimiento institucional de capacitación, competencias y constancias.',
            navigation: [
                self::dashboardItem(),
                self::item('Capacitación', 'rh.training.index', 'courses', 'rh.training.*'),
                self::item('Personal', 'rh.staff.index', 'users', 'rh.staff.*'),
                self::item('Cursos requeridos', 'rh.competencies.index', 'catalog', 'rh.competencies.*'),
                self::item('Evidencias', 'rh.evidences.index', 'evidence', 'rh.evidences.*'),
                self::item('Constancias', 'rh.certificates.index', 'certificate', 'rh.certificates.*'),
                self::item('Reportes', 'rh.reports.index', 'report', 'rh.reports.*'),
            ],
            actions: [
                self::action('Capacitación', 'Consulta la oferta interna y su avance.', 'rh.training.index', 'courses'),
                self::action('Personal', 'Consulta expedientes y trayectorias formativas.', 'rh.staff.index', 'users'),
                self::action('Cursos requeridos', 'Revisa la formación institucional necesaria.', 'rh.competencies.index', 'catalog'),
                self::action('Reportes', 'Consulta los indicadores consolidados.', 'rh.reports.index', 'report'),
            ],
        );
    }

    /** @return array<string, mixed> */
    private static function quality(): array
    {
        return self::portal(
            key: 'quality', role: 'Calidad Académica', name: 'Portal de Calidad Académica', title: 'Panel de Calidad Académica',
            description: 'Consulta evidencias, evaluaciones e indicadores para acreditación.',
            navigation: [
                self::dashboardItem(),
                self::item('Evidencias', 'quality.evidences.index', 'evidence', 'quality.evidences.*'),
                self::item('Evaluaciones', 'quality.evaluations.index', 'evaluation', 'quality.evaluations.*'),
                self::item('CACEI', 'quality.cacei.index', 'report', 'quality.cacei.*'),
                self::item('ABET', 'quality.abet.index', 'report', 'quality.abet.*'),
                self::item('ISO', 'quality.iso.index', 'settings', 'quality.iso.*'),
                self::item('Mejora continua', 'quality.improvement.index', 'path', 'quality.improvement.*'),
                self::item('Reportes', 'quality.reports.index', 'report', 'quality.reports.*'),
            ],
            actions: [
                self::action('Evidencias', 'Consulta los expedientes institucionales.', 'quality.evidences.index', 'evidence'),
                self::action('Evaluaciones', 'Revisa instrumentos y resultados académicos.', 'quality.evaluations.index', 'evaluation'),
                self::action('CACEI', 'Da seguimiento a categorías e indicadores.', 'quality.cacei.index', 'report'),
                self::action('ABET', 'Consulta Student Outcomes y nivel de logro.', 'quality.abet.index', 'report'),
                self::action('ISO', 'Revisa procesos, auditorías y hallazgos.', 'quality.iso.index', 'settings'),
                self::action('Mejora continua', 'Consulta planes de acción y compromisos.', 'quality.improvement.index', 'path'),
                self::action('Reportes', 'Consulta resultados consolidados de calidad.', 'quality.reports.index', 'report'),
            ],
        );
    }

    /** @return array<string, mixed> */
    private static function continuingEducation(): array
    {
        return self::portal(
            key: 'continuing-education', role: 'Educación Continua', name: 'Portal de Educación Continua', title: 'Panel de Educación Continua',
            description: 'Gestiona oferta externa, inscripciones, pagos y constancias.',
            navigation: [
                self::dashboardItem(),
                self::item('Oferta', 'continuing-education.offers.index', 'catalog', 'continuing-education.offers.*'),
                self::item('Participantes externos', 'continuing-education.external-participants.index', 'users', 'continuing-education.external-participants.*'),
                self::item('Inscripciones', 'continuing-education.enrollments.index', 'evaluation', 'continuing-education.enrollments.*'),
                self::item('Pagos', 'continuing-education.payments.index', 'payment', 'continuing-education.payments.*'),
                self::item('Constancias', 'continuing-education.certificates.index', 'certificate', 'continuing-education.certificates.*'),
                self::item('Reportes', 'continuing-education.reports.index', 'report', 'continuing-education.reports.*'),
            ],
            actions: [
                self::action('Oferta externa', 'Consulta las actividades de educación continua.', 'continuing-education.offers.index', 'catalog'),
                self::action('Participantes', 'Administra las cuentas de participantes externos.', 'continuing-education.external-participants.index', 'users'),
                self::action('Pagos', 'Consulta comprobantes y estados de pago.', 'continuing-education.payments.index', 'payment'),
                self::action('Reportes', 'Revisa resultados de la oferta externa.', 'continuing-education.reports.index', 'report'),
            ],
        );
    }

    /** @return array<string, mixed> */
    private static function areaManager(): array
    {
        return self::portal(
            key: 'area-manager', role: 'Responsable de área', name: 'Portal del responsable de área', title: 'Panel del responsable de área',
            description: 'Seguimiento de actividades, participantes, inscripciones y evidencias del área.',
            navigation: [
                self::dashboardItem(),
                self::item('Actividades', 'area-manager.activities.index', 'courses', 'area-manager.activities.*'),
                self::item('Participantes', 'area-manager.participants.index', 'users', 'area-manager.participants.*'),
                self::item('Inscripciones', 'area-manager.enrollments.index', 'evaluation', 'area-manager.enrollments.*'),
                self::item('Evidencias', 'area-manager.evidences.index', 'evidence', 'area-manager.evidences.*'),
                self::item('Reportes', 'area-manager.reports.index', 'report', 'area-manager.reports.*'),
            ],
            actions: [
                self::action('Actividades', 'Consulta la oferta administrada por tu área.', 'area-manager.activities.index', 'courses'),
                self::action('Participantes', 'Revisa el personal adscrito y su formación.', 'area-manager.participants.index', 'users'),
                self::action('Evidencias', 'Da seguimiento a los documentos del área.', 'area-manager.evidences.index', 'evidence'),
                self::action('Reportes', 'Consulta los indicadores consolidados.', 'area-manager.reports.index', 'report'),
            ],
        );
    }

    /** @return array<string, mixed> */
    private static function unsupported(User $user): array
    {
        return self::portal(
            key: 'unsupported',
            role: $user->getRoleNames()->first() ?? 'Usuario',
            name: 'Plataforma Institucional de Formación y Microcredenciales',
            title: 'Panel institucional',
            description: 'No hay un portal operativo asignado a esta cuenta.',
            navigation: [],
            actions: [],
        );
    }

    /** @param array<int, array<string, mixed>> $navigation @param array<int, array<string, mixed>> $actions @return array<string, mixed> */
    private static function portal(string $key, string $role, string $name, string $title, string $description, array $navigation, array $actions, ?string $profileRoute = null, ?string $profileLabel = null): array
    {
        return compact('key', 'role', 'name', 'title', 'description', 'navigation', 'actions', 'profileRoute', 'profileLabel');
    }

    /** @return array<string, mixed> */
    private static function dashboardItem(): array
    {
        return self::item('Inicio', 'dashboard', 'home', ['dashboard', '*.dashboard']);
    }

    /** @return array<string, mixed> */
    private static function dashboardSectionItem(string $label, ?string $section, string $icon): array
    {
        return [
            'type' => 'link',
            'label' => $label,
            'route' => 'dashboard',
            'parameters' => $section ? ['section' => $section] : [],
            'icon' => $icon,
            'active' => ['dashboard'],
            'section' => $section,
        ];
    }

    /** @param string|array<int, string> $active @return array<string, mixed> */
    private static function item(string $label, string $route, string $icon, string|array $active): array
    {
        return ['type' => 'link', 'label' => $label, 'route' => $route, 'icon' => $icon, 'active' => (array) $active];
    }

    /** @return array<string, mixed> */
    private static function section(string $label): array
    {
        return ['type' => 'section', 'label' => $label];
    }

    /** @return array<string, mixed> */
    private static function action(string $label, string $description, string $route, string $icon): array
    {
        return ['label' => $label, 'description' => $description, 'url' => route($route), 'icon' => $icon];
    }

    /** @return array<string, mixed> */
    private static function dashboardAction(string $label, string $description, string $section, string $icon): array
    {
        return ['label' => $label, 'description' => $description, 'url' => route('dashboard', ['section' => $section]), 'icon' => $icon];
    }
}
