<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Microcredential;
use App\Services\Microcredentials\MicrocredentialPayloadService;
use Illuminate\Http\JsonResponse;

class MicrocredentialController extends Controller
{
    public function show(
        Microcredential $microcredential,
        MicrocredentialPayloadService $payloadService,
    ): JsonResponse {
        return response()->json([
            'data' => $payloadService->buildPayload($microcredential->load('user')),
        ]);
    }
}
