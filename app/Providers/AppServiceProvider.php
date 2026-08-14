<?php

namespace App\Providers;

use App\Models\Activity;
use App\Models\Area;
use App\Models\AttendanceRecord;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\CertificationProgram;
use App\Models\Competency;
use App\Models\DigitalResource;
use App\Models\DiplomaProgram;
use App\Models\Enrollment;
use App\Models\Evaluation;
use App\Models\Evidence;
use App\Models\EvidenceReview;
use App\Models\LearningPath;
use App\Models\Microcredential;
use App\Models\Payment;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\TrainingProgram;
use App\Models\User;
use App\Models\Webinar;
use App\Observers\AuditableObserver;
use App\Observers\EnrollmentLearningPathObserver;
use App\Observers\PaymentEnrollmentObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Microsoft\Provider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        foreach ([
            User::class,
            Area::class,
            TrainingProgram::class,
            Activity::class,
            Enrollment::class,
            AttendanceRecord::class,
            Evidence::class,
            EvidenceReview::class,
            Evaluation::class,
            Payment::class,
            Survey::class,
            SurveyQuestion::class,
            DigitalResource::class,
            Webinar::class,
            LearningPath::class,
            Competency::class,
            CertificationProgram::class,
            DiplomaProgram::class,
            Certificate::class,
            CertificateTemplate::class,
            Microcredential::class,
        ] as $model) {
            $model::observe(AuditableObserver::class);
        }

        Enrollment::observe(EnrollmentLearningPathObserver::class);
        Payment::observe(PaymentEnrollmentObserver::class);

        Event::listen(function (SocialiteWasCalled $event): void {
            $event->extendSocialite('microsoft', Provider::class);
        });

        RateLimiter::for('login', fn (Request $request) => Limit::perMinute(5)
            ->by(Str::lower((string) $request->input('email')).'|'.$request->ip()));

        RateLimiter::for('register', fn (Request $request) => Limit::perMinute(3)
            ->by(Str::lower((string) $request->input('email')).'|'.$request->ip()));

        RateLimiter::for('public-certificates', fn (Request $request) => Limit::perMinute(10)
            ->by($request->ip()));

        RateLimiter::for('downloads', fn (Request $request) => Limit::perMinute(5)
            ->by((string) ($request->user()?->id ?? $request->ip())));

        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(30)
            ->by($request->ip()));

        RateLimiter::for('authenticated', fn (Request $request) => Limit::perMinute(60)
            ->by((string) ($request->user()?->id ?? $request->ip())));

        RateLimiter::for('sensitive-actions', fn (Request $request) => Limit::perMinute(10)
            ->by((string) ($request->user()?->id ?? $request->ip())));
    }
}
