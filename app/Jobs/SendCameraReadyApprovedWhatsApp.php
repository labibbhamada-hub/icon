<?php

namespace App\Jobs;

use App\Models\Participant;
use App\Models\Submission;
use App\Services\FonnteService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendCameraReadyApprovedWhatsApp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public int $tries = 3;
    public int $timeout = 120;
    public function __construct(
        public int $participantId,
        public int $submissionId,
    ) {}
    public function handle(FonnteService $fonnte): void
    {
        $participant = Participant::find($this->participantId);
        $submission = Submission::find($this->submissionId);
        if (!$participant || !$submission) {
            return;
        }
        if (!$participant->phone) {
            return;
        }
        $fonnte->sendCameraReadyApproved(
            $participant,
            $submission
        );
    }
}
