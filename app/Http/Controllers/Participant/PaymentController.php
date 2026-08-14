<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Participant\PaymentRequest;
use App\Models\Participant;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index()
    {
        $participantIds = Participant::where(
            'user_id',
            Auth::id()
        )
            ->pluck('id');

        $payments = Payment::with(
            'participant.conference'
        )
            ->whereIn(
                'participant_id',
                $participantIds
            )
            ->latest()
            ->get();

        return view('participant.payments.index', compact('payments'));
    }

    public function create()
    {
        $participants = Participant::with('conference')
            ->where('user_id', Auth::id())
            ->where('registration_status', 'pending')
            ->get();

        return view('participant.payments.create', compact('participants'));
    }

    public function store(PaymentRequest $request)
    {
        $data = $request->validated();

        $participant = Participant::where(
            'id',
            $data['participant_id']
        )
            ->where(
                'user_id',
                Auth::id()
            )
            ->firstOrFail();

        $paymentCode = $this->generatePaymentCode();

        $proofFile = $request
            ->file('proof_file')
            ->store(
                'payments/proofs',
                'public'
            );

        Payment::create([
            'participant_id' =>
            $participant->id,

            'payment_code' =>
            $paymentCode,

            'amount' =>
            $data['amount'],

            'payment_method' =>
            $data['payment_method'],

            'proof_file' =>
            $proofFile,

            'status' =>
            'pending',

            'notes' =>
            $data['notes'] ?? null,

            'paid_at' =>
            $data['paid_at'],
        ]);

        return redirect()
            ->route('participant.payments.index')
            ->with(
                'success',
                'Payment proof submitted successfully.'
            );
    }

    private function generatePaymentCode(): string
    {
        do {
            $code = 'PAY-ICON26-' .
                strtoupper(
                    Str::random(6)
                );
        } while (
            Payment::where(
                'payment_code',
                $code
            )->exists()
        );

        return $code;
    }
}
