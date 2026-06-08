<?php

namespace App\Http\Controllers\ContinuingEducation;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::query()
            ->visibleTo($request->user())
            ->with(['user', 'activity'])
            ->latest()
            ->paginate(20);

        return view('continuing-education.payments.index', compact('payments'));
    }
}
