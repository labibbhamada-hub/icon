<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentVerifiedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Payment $payment
    ) {}

    public function build()
    {
        return $this
            ->subject('Payment Verified - ICON 2026')
            ->view('emails.payment-verified');
    }
}
