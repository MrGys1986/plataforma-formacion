<?php

use App\Http\Controllers\Api\MicrocredentialController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function (): void {
    Route::get('/microcredentials/{microcredential}', [MicrocredentialController::class, 'show'])
        ->middleware(['signed', 'throttle:api'])
        ->name('microcredentials.show');

    // TODO: proteger send/status con auth:sanctum, permission, firma de payload y queues.
});
