<?php

namespace App\Observers;

use App\Models\Certificate;
use App\Services\Notifications\TrainingNotificationService;

class CertificateNotificationObserver
{
    public function __construct(private readonly TrainingNotificationService $notifications) {}

    public function created(Certificate $certificate): void
    {
        $this->notifications->certificateChanged($certificate, true);
    }

    public function updated(Certificate $certificate): void
    {
        $this->notifications->certificateChanged($certificate, false);
    }
}
