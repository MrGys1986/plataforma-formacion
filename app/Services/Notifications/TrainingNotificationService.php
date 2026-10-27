<?php

namespace App\Services\Notifications;

use App\Models\Activity;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\NotificationLog;
use App\Models\User;

class TrainingNotificationService
{
    public function enrollmentChanged(Enrollment $enrollment, bool $created): void
    {
        $activity = $enrollment->activity()->first();
        $student = $enrollment->user()->first();

        if (! $activity || ! $student) {
            return;
        }

        $metadata = ['activity' => $activity->public_id, 'enrollment' => $enrollment->public_id];
        $name = $activity->participant_name;

        if ($this->requestIsReady($enrollment, $activity)
            && ($created || ($enrollment->wasChanged(['status', 'requested_at', 'payment_status'])
                && ! $this->requestWasReady($enrollment, $activity)))) {
            $instructor = $activity->instructor;
            $recipients = $instructor?->status === 'activo' && $instructor->id !== $student->id
                ? collect([$instructor])
                : User::role('Superadministrador')->where('status', 'activo')->whereKeyNot($student->id)->get();

            foreach ($recipients as $recipient) {
                $this->send($recipient, 'enrollment_requested', 'Nueva solicitud de inscripción',
                    $student->name.' solicita inscribirse a «'.$name.'».', $metadata);
            }
        }

        if (($created || $enrollment->wasChanged('status')) && in_array($enrollment->status, ['aprobada', 'rechazada'], true)) {
            $approved = $enrollment->status === 'aprobada';
            $message = $approved
                ? 'Tu inscripción a «'.$name.'» fue aprobada.'
                : 'Tu inscripción a «'.$name.'» fue rechazada.'.($enrollment->rejection_reason ? ' Motivo: '.$enrollment->rejection_reason : '');
            $this->send($student, $approved ? 'enrollment_approved' : 'enrollment_rejected',
                $approved ? 'Inscripción aprobada' : 'Inscripción rechazada', $message, $metadata);
        }

        if ($enrollment->status === 'aprobada' && $enrollment->final_score !== null
            && ($created || $enrollment->wasChanged(['final_score', 'completion_status']))) {
            $completed = $enrollment->completion_status === 'completado';
            $this->send($student, 'grade_updated', $completed ? 'Formación completada' : 'Calificación actualizada',
                'Tu calificación en «'.$name.'» es '.$enrollment->final_score.'.'.($completed ? ' La formación está completada.' : ''), $metadata);
        }
    }

    public function certificateChanged(Certificate $certificate, bool $created): void
    {
        if ($certificate->status !== 'emitida' || ! $certificate->file_upload_id
            || (! $created && ! $certificate->wasChanged(['status', 'file_upload_id', 'user_id']))
            || ! $certificate->fileUpload()->exists()) {
            return;
        }

        $student = $certificate->user()->first();
        if (! $student) {
            return;
        }

        $replaced = ! $created && $certificate->wasChanged('file_upload_id') && $certificate->getRawOriginal('file_upload_id') !== null;
        $name = $certificate->activity()->first()?->participant_name ?? 'tu formación';
        $this->send($student, 'certificate_available', $replaced ? 'Constancia actualizada' : 'Constancia disponible',
            'Ya puedes descargar '.($replaced ? 'la nueva constancia' : 'tu constancia').' de «'.$name.'».',
            ['certificate' => $certificate->public_id]);
    }

    private function requestIsReady(Enrollment $enrollment, Activity $activity): bool
    {
        return $enrollment->status === 'solicitada' && $enrollment->requested_at !== null
            && (! $activity->requires_payment || in_array($enrollment->payment_status, ['validado', 'pagado'], true));
    }

    private function requestWasReady(Enrollment $enrollment, Activity $activity): bool
    {
        return $enrollment->getRawOriginal('status') === 'solicitada'
            && $enrollment->getRawOriginal('requested_at') !== null
            && (! $activity->requires_payment || in_array($enrollment->getRawOriginal('payment_status'), ['validado', 'pagado'], true));
    }

    private function send(User $recipient, string $type, string $subject, string $message, array $metadata): void
    {
        if ($recipient->status !== 'activo') {
            return;
        }

        NotificationLog::create([
            'user_id' => $recipient->id, 'notification_type' => $type, 'subject' => $subject,
            'message' => $message, 'metadata' => $metadata, 'channel' => 'in_app',
            'status' => 'enviada', 'sent_at' => now(),
        ]);
    }
}
