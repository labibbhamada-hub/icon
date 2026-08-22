<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Participant\PaymentRequest;
use App\Models\Participant;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
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

        $payments = Payment::with([
            'participant.conference.setting',
            'verifier',
        ])
            ->whereIn(
                'participant_id',
                $participantIds
            )
            ->latest()
            ->get();

        return view(
            'participant.payments.index',
            compact('payments')
        );
    }

    public function create()
    {
        $participants = Participant::with([
            'conference.setting',
            'conference.configuration',
        ])
            ->where('user_id', Auth::id())
            ->where('registration_status', 'pending')
            ->whereHas('conference.setting', function ($query) {
                $query
                    ->where('is_active', true)
                    ->where('payment_enabled', true)
                    ->where('maintenance_mode', false);
            })
            ->get();

        return view(
            'participant.payments.create',
            compact('participants')
        );
    }

    public function store(PaymentRequest $request)
    {
        $data = $request->validated();

        $participant = Participant::with([
            'conference.setting',
            'conference.configuration',
        ])
            ->where('id', $data['participant_id'])
            ->where('user_id', Auth::id())
            ->where('registration_status', 'pending')
            ->firstOrFail();

        // 1. Cek apakah payment sedang aktif
        if (
            !$participant->conference?->setting?->payment_enabled
            || $participant->conference?->setting?->maintenance_mode
        ) {
            return back()
                ->with(
                    'error',
                    'Payment submission is currently unavailable.'
                );
        }

        // 2. Ambil konfigurasi payment conference
        $configuration =
            $participant->conference->configuration;

        // 3. Pastikan configuration sudah tersedia
        if (!$configuration) {
            return back()
                ->with(
                    'error',
                    'Payment configuration has not been set for this conference.'
                );
        }

        // 4. Pastikan rekening bank sudah lengkap
        if (
            empty($configuration->bank_name)
            || empty($configuration->account_number)
            || empty($configuration->account_name)
        ) {
            return back()
                ->with(
                    'error',
                    'Payment account has not been configured for this conference.'
                );
        }

        // 5. Tentukan nominal otomatis
        $amount =
            $participant->participant_type === 'student'
            ? $configuration->student_fee
            : $configuration->regular_fee;

        // 6. Pastikan nominal sudah diatur
        if ($amount <= 0) {
            return back()
                ->with(
                    'error',
                    'Registration fee has not been configured for this conference.'
                );
        }

        // 7. Generate payment code
        $paymentCode =
            $this->generatePaymentCode();

        // 8. Simpan bukti transfer
        $proofFile = $request
            ->file('proof_file')
            ->store(
                'payments/proofs',
                'local'
            );

        // 9. Simpan payment
        Payment::create([
            'participant_id' => $participant->id,
            'payment_code' => $paymentCode,
            'amount' => $amount,
            'payment_method' => 'bank_transfer',
            'proof_file' => $proofFile,
            'status' => 'pending',
            'notes' => $data['notes'] ?? null,
            'paid_at' => $data['paid_at'],
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
