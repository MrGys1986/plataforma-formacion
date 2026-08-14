<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payments\StorePaymentRequest;
use App\Models\Activity;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Services\Files\ManagedFileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Payment::class);

        $payments = Payment::query()
            ->visibleTo($request->user())
            ->with(['activity', 'proofFile'])
            ->latest()
            ->paginate(12);

        return view('participant.payments.index', compact('payments'));
    }

    public function store(StorePaymentRequest $request, ManagedFileService $files): RedirectResponse
    {
        $activity = Activity::where('public_id', $request->validated('activity_public_id'))->firstOrFail();
        $enrollment = Enrollment::where('public_id', $request->validated('enrollment_public_id'))
            ->where('user_id', $request->user()->id)
            ->where('activity_id', $activity->id)
            ->firstOrFail();
        abort_unless($activity->requires_payment, 422, 'La actividad no requiere pago.');
        abort_unless(
            abs((float) $request->validated('amount') - (float) $activity->cost) < 0.01,
            422,
            'El importe no coincide con el costo del curso.',
        );

        $proof = $files->store($request->file('proof'), 'payment-proofs/'.$request->user()->public_id, $request->user()->id);
        try {
            DB::transaction(function () use ($request, $activity, $enrollment, $proof): void {
                Payment::create([
                    'enrollment_id' => $enrollment->id,
                    'user_id' => $request->user()->id,
                    'activity_id' => $activity->id,
                    'proof_file_id' => $proof->id,
                    'amount' => $request->validated('amount'),
                    'currency' => $request->validated('currency'),
                    'payment_method' => $request->validated('payment_method'),
                    'status' => 'pendiente',
                ]);
                $enrollment->update([
                    'payment_status' => 'pendiente',
                ]);
            });
        } catch (Throwable $exception) {
            $files->purge($proof);
            throw $exception;
        }

        return back()->with('status', 'El comprobante fue enviado para revisión. La solicitud llegará al profesor cuando el pago sea validado.');
    }
}
