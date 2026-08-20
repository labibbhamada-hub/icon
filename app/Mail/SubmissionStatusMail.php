<?php

namespace App\Mail;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubmissionStatusMail extends Mailable
{
    public function __construct(
        public Submission $submission,
        public string $message
    ) {}

    public function build()
    {
        return $this
            ->subject('Submission Status Update - ICON 2026')
            ->view('emails.submission-status');
    }
}
