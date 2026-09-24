<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Evidence;
use App\Models\Payment;
use App\Models\User;
use App\Support\PortalConfiguration;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->usesFilamentDashboard()) {
            return redirect()->route('filament.admin.pages.dashboard');
        }

        $portal = PortalConfiguration::forUser($user);

        abort_if($portal['key'] === 'unsupported', 403, 'Esta cuenta no tiene un portal operativo asignado.');

        [$stats, $attention, $recent] = match ($portal['key']) {
            'professor' => $this->professorData($user),
            'student' => $this->studentData($user),
            'external' => $this->externalData($user),
            default => abort(403, 'Este perfil debe utilizar el panel administrativo.'),
        };

        return view('portal.dashboard', compact('portal', 'stats', 'attention', 'recent'));
    }

    /** @return array{array<int, array<string, mixed>>, array<int, array<string, mixed>>, array<int, array<string, mixed>>} */
    private function professorData(User $user): array
    {
        $activities = $user->instructedActivities();
        $activityIds = (clone $activities)->pluck('id');

        return [[
            $this->stat('Cursos asignados', (clone $activities)->count(), 'Actividad docente', 'teaching'),
            $this->stat('Cursos activos', (clone $activities)->whereIn('status', ['publicado', 'en_inscripcion', 'en_curso'])->count(), 'En este momento', 'courses', 'emerald'),
            $this->stat('Participantes', Enrollment::query()->whereIn('activity_id', $activityIds)->count(), 'En tus grupos', 'users'),
            $this->stat('Mi formación', $user->enrollments()->count(), 'Cursos como participante', 'catalog'),
        ], [
            $this->attention('Inscripciones solicitadas', Enrollment::query()->whereIn('activity_id', $activityIds)->where('status', 'solicitada')->count(), route('participant.professor.teaching.index')),
            $this->attention('Evidencias por revisar', Evidence::query()->whereIn('activity_id', $activityIds)->where('status', 'pendiente')->count(), route('participant.professor.teaching.index')),
        ], []];
    }

    /** @return array{array<int, array<string, mixed>>, array<int, array<string, mixed>>, array<int, array<string, mixed>>} */
    private function studentData(User $user): array
    {
        $enrollments = $user->enrollments();

        return [[
            $this->stat('Cursos inscritos', (clone $enrollments)->count(), 'Tu formación', 'courses'),
            $this->stat('En progreso', (clone $enrollments)->where('status', 'aprobada')->where('completion_status', '!=', 'completado')->count(), 'Continúa aprendiendo', 'path', 'blue'),
            $this->stat('Completados', (clone $enrollments)->where('completion_status', 'completado')->count(), 'Cursos terminados', 'evaluation', 'emerald'),
            $this->stat('Constancias', $user->certificates()->where('status', 'emitida')->count(), 'Disponibles', 'certificate', 'violet'),
        ], [], $this->recentEnrollments($user)];
    }

    /** @return array{array<int, array<string, mixed>>, array<int, array<string, mixed>>, array<int, array<string, mixed>>} */
    private function externalData(User $user): array
    {
        $enrollments = $user->enrollments();
        $pendingPayments = Payment::query()->where('user_id', $user->id)->where('status', 'pendiente')->count();

        return [[
            $this->stat('Inscripciones', (clone $enrollments)->count(), 'Total registrado', 'courses'),
            $this->stat('Cursos activos', (clone $enrollments)->where('status', 'aprobada')->where('completion_status', '!=', 'completado')->count(), 'En progreso', 'path'),
            $this->stat('Pagos pendientes', $pendingPayments, 'Por revisar', 'payment', 'amber'),
            $this->stat('Constancias', $user->certificates()->where('status', 'emitida')->count(), 'Disponibles', 'certificate', 'violet'),
        ], [
            $this->attention('Pagos pendientes', $pendingPayments, route('participant.payments.index')),
        ], $this->recentEnrollments($user)];
    }

    /** @return array<int, array<string, mixed>> */
    private function recentEnrollments(User $user): array
    {
        return $user->enrollments()
            ->with('activity')
            ->latest()
            ->limit(4)
            ->get()
            ->map(fn (Enrollment $enrollment): array => [
                'title' => $enrollment->activity?->name ?? 'Curso no disponible',
                'meta' => $enrollment->created_at?->format('d/m/Y'),
                'status' => ucfirst(str_replace('_', ' ', $enrollment->status)),
                'url' => route('participant.my-courses.index'),
            ])
            ->all();
    }

    /** @return array<string, mixed> */
    private function stat(string $label, int $value, string $description, string $icon, string $tone = 'blue'): array
    {
        return compact('label', 'value', 'description', 'icon', 'tone');
    }

    /** @return array<string, mixed> */
    private function attention(string $label, int $value, string $url): array
    {
        return compact('label', 'value', 'url');
    }
}
