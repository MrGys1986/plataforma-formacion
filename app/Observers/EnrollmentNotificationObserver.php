<?php

namespace App\Observers;

use App\Models\Enrollment;
use App\Services\Notifications\TrainingNotificationService;

class EnrollmentNotificationObserver
{
    public function __construct(private readonly TrainingNotificationService $notifications) {}

    public function created(Enrollment $enrollment): void
    {
        $this->notifications->enrollmentChanged($enrollment, true);
    }

    public function updated(Enrollment $enrollment): void
    {
        $this->notifications->enrollmentChanged($enrollment, false);
    }
}
