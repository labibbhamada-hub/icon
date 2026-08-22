<?php

namespace App\Mail;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubmissionStatusMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 120;

    public function __construct(
        public Submission $submission,
        public string $statusMessage
    ) {}

    public function build()
    {
        return $this
            ->subject(
                'Submission Status Update - ICON 2026'
            )
            ->view(
                'emails.submission-status'
            );
    }
}
