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

class SendCameraReadyCorrectionWhatsApp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public int $tries = 3;
    public int $timeout = 120;
    public function __construct(
        public int $participantId,
        public int $submissionId
    ) {}
    public function handle(FonnteService $fonnte): void
    {
        $participant = Participant::find($this->participantId);
        $submission = Submission::find($this->submissionId);
        if (!$participant?->phone || !$submission) {
            return;
        }
        $message = "ICON 2026\n\n"
            . "Dear {$participant->full_name},\n\n"
            . "Your camera-ready paper requires correction.\n\n"
            . "Submission: {$submission->submission_code}\n"
            . "Title: {$submission->title}\n\n"
            . "Correction Reason:\n"
            . "{$submission->camera_ready_correction_reason}\n\n"
            . "Please log in to the ICON 2026 Participant Portal and upload the corrected camera-ready manuscript.\n\n"
            . "Regards,\n"
            . "ICON 2026 Secretariat\n"
            . "Universitas Bhamada Slawi";
        $fonnte->send(
            $participant->phone,
            $message
        );
    }
}
