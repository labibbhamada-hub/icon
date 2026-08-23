<?php

namespace App\Jobs;

use App\Models\Participant;
use App\Models\Payment;
use App\Services\FonnteService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPaymentVerifiedWhatsApp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public int $tries = 3;
    public int $timeout = 120;
    public function __construct(
        public int $participantId,
        public int $paymentId,
    ) {}
    public function handle(FonnteService $fonnte): void
    {
        $participant = Participant::find($this->participantId);
        $payment = Payment::find($this->paymentId);
        if (!$participant || !$payment) {
            return;
        }
        if (!$participant->phone) {
            return;
        }
        $fonnte->sendPaymentVerified(
            $participant,
            $payment
        );
    }
}
