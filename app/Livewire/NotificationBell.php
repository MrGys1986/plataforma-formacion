<?php

namespace App\Livewire;

use App\Filament\Resources\EnrollmentResource;
use App\Models\Activity;
use App\Models\Enrollment;
use App\Models\NotificationLog;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationBell extends Component
{
    use WithPagination;

    #[Locked]
    public bool $admin = false;

    public function boot(): void
    {
        abort_unless(auth()->check(), 401);
        abort_unless(auth()->user()->status === 'activo', 403);
    }

    public function markAsRead(int $id): void
    {
        $notification = auth()->user()->inAppNotifications()->find($id);
        abort_unless($notification, 404);
        if ($notification->read_at === null) {
            $notification->update(['read_at' => now()]);
        }
    }

    public function markAllAsRead(): void
    {
        auth()->user()->inAppNotifications()->whereNull('read_at')->update(['read_at' => now()]);
    }

    public function open(int $id): void
    {
        $notification = auth()->user()->inAppNotifications()->find($id);
        abort_unless($notification, 404);
        $this->markAsRead($id);
        $this->redirect($this->destination($notification));
    }

    public function render(): View
    {
        return view('livewire.notification-bell', [
            'unreadCount' => auth()->user()->inAppNotifications()->whereNull('read_at')->count(),
            'notifications' => auth()->user()->inAppNotifications()->latest('id')->paginate(6, pageName: 'notificationsPage'),
        ]);
    }

    private function destination(NotificationLog $notification): string
    {
        $user = auth()->user();
        $metadata = $notification->metadata ?? [];

        if ($notification->notification_type === 'enrollment_requested') {
            $activity = Activity::where('public_id', $metadata['activity'] ?? '')->first();
            if ($activity && $activity->instructor_id === $user->id && $user->hasRole('Profesor')) {
                return route('participant.professor.teaching.show', $activity);
            }
            if ($activity && $user->usesFilamentDashboard() && Gate::allows('view', $activity) && Gate::allows('viewAny', Enrollment::class)) {
                return EnrollmentResource::getUrl('index', ['activity' => $activity->id]);
            }
        } elseif ($user->hasAnyRole(['Profesor', 'Alumno', 'Externo'])) {
            if ($notification->notification_type === 'certificate_available') {
                return route('participant.certificates.index');
            }
            $enrollment = $user->enrollments()->where('public_id', $metadata['enrollment'] ?? '')->first();
            if ($enrollment?->activity) {
                return route('participant.learning.show', $enrollment);
            }

            return route('participant.my-courses.index');
        }

        return $user->usesFilamentDashboard() ? route('filament.admin.pages.dashboard') : route('dashboard');
    }
}
