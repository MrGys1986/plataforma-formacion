<?php

use App\Models\Activity;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('files:cleanup-orphans --execute --days=30')
    ->monthly()
    ->withoutOverlapping();

Schedule::command('files:purge-deleted')
    ->dailyAt('02:30')
    ->withoutOverlapping();

Schedule::call(function (): void {
    Activity::query()
        ->whereDate('end_date', '<', today())
        ->whereIn('status', ['publicado', 'en_inscripcion', 'cupo_lleno', 'en_curso'])
        ->update(['status' => 'archivado']);
})->name('archive-expired-training-activities')
    ->dailyAt('00:15')
    ->withoutOverlapping();
