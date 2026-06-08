<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        RateLimiter::for('login', fn (Request $request) => Limit::perMinute(5)
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
