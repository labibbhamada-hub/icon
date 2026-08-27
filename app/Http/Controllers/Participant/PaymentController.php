<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Participant\PaymentRequest;
use App\Models\Participant;
use App\Models\Payment;
use App\Services\PaymentCalculationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

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
            'participant.conference',
            'participant.registrationType',
            'paymentMethod',
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

    public function create(
        PaymentCalculationService $paymentCalculationService
    ) {
        $participants = Participant::with([
            'conference.setting',
            'conference.paymentMethods' => function ($query) {
                $query
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name');
            },
            'registrationType',
            'submissions',
        ])
            ->where(
                'user_id',
                Auth::id()
            )
            ->whereHas(
                'conference.setting',
                function ($query) {
                    $query
                        ->where(
                            'is_active',
                            true
                        )
                        ->where(
                            'payment_enabled',
                            true
                        )
                        ->where(
                            'maintenance_mode',
                            false
                        );
                }
            )
            ->get()
            ->filter(function (Participant $participant) use (
                $paymentCalculationService
            ) {
                return $paymentCalculationService
                    ->canPay($participant);
            })
            ->filter(function (Participant $participant) {
                return !$this->hasPendingPayment(
                    $participant
                );
            })
            ->values();

        return view(
            'participant.payments.create',
            compact('participants')
        );
    }

    public function store(
        PaymentRequest $request,
        PaymentCalculationService $paymentCalculationService
    ) {
        $data = $request->validated();

        $participant = Participant::with([
            'conference.setting',
            'conference.paymentMethods',
            'registrationType',
            'submissions',
        ])
            ->where(
                'id',
                $data['participant_id']
            )
            ->where(
                'user_id',
                Auth::id()
            )
            ->firstOrFail();

        if (
            !$participant->conference?->setting?->payment_enabled
            || $participant->conference?->setting?->maintenance_mode
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Payment submission is currently unavailable.'
                );
        }

        if (
            !$paymentCalculationService->canPay(
                $participant
            )
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'This registration is not yet eligible for payment.'
                );
        }

        if (
            $this->hasPendingPayment(
                $participant
            )
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'A payment for this registration is already being processed.'
                );
        }

        $calculation =
            $paymentCalculationService
            ->calculate($participant);

        if ($calculation['outstanding_amount'] <= 0) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'There is no outstanding payment for this registration.'
                );
        }

        DB::beginTransaction();

        try {
            $paymentCode =
                $this->generatePaymentCode();

            $proofFile = $request
                ->file('proof_file')
                ->store(
                    'payments/proofs',
                    'local'
                );

            Payment::create([
                'participant_id' =>
                $participant->id,

                'payment_method_id' =>
                $data['payment_method_id'],

                'payment_code' =>
                $paymentCode,

                'amount' =>
                $calculation['outstanding_amount'],

                'proof_file' =>
                $proofFile,

                'status' =>
                'pending',

                'notes' =>
                $data['notes'] ?? null,

                'paid_at' =>
                $data['paid_at'],
            ]);

            DB::commit();

            return redirect()
                ->route(
                    'participant.payments.index'
                )
                ->with(
                    'success',
                    'Payment proof submitted successfully.'
                );
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    private function hasPendingPayment(
        Participant $participant
    ): bool {
        return $participant
            ->payments()
            ->where(
                'status',
                'pending'
            )
            ->exists();
    }

    private function generatePaymentCode(): string
    {
        do {
            $code =
                'PAY-ICON26-' .
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
