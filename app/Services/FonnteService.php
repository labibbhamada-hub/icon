<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FonnteService
{
    public function send(string $target, string $message): bool
    {
        $token = config('services.fonnte.token');
        $url = config('services.fonnte.url');
        if (!$token || !$url || !$target) {
            return false;
        }
        $response = Http::withHeaders([
            'Authorization' => $token,
        ])->asForm()->post($url, [
            'target' => $target,
            'message' => $message,
        ]);
        return $response->successful();
    }
    public function sendPaymentVerified($participant, $payment): bool
    {
        if (!$participant?->phone) {
            return false;
        }
        $message = "ICON 2026\n\n"
            . "Dear {$participant->full_name},\n\n"
            . "Your payment has been successfully verified.\n\n"
            . "Registration: {$participant->registration_number}\n"
            . "Status: Confirmed\n\n"
            . "Thank you for registering for ICON 2026.\n\n"
            . "Regards,\n"
            . "ICON 2026 Secretariat\n"
            . "Universitas Bhamada Slawi";
        return $this->send(
            $participant->phone,
            $message
        );
    }
    public function sendRevisionRequired($participant, $submission): bool
    {
        if (!$participant?->phone) {
            return false;
        }
        $message = "ICON 2026\n\n"
            . "Dear {$participant->full_name},\n\n"
            . "Your paper requires revision based on the reviewer feedback.\n\n"
            . "Submission: {$submission->submission_code}\n"
            . "Title: {$submission->title}\n\n"
            . "Please log in to the ICON 2026 Participant Portal and upload your revised manuscript.\n\n"
            . "Regards,\n"
            . "ICON 2026 Secretariat\n"
            . "Universitas Bhamada Slawi";
        return $this->send(
            $participant->phone,
            $message
        );
    }
    public function sendSubmissionAccepted($participant, $submission): bool
    {
        if (!$participant?->phone) {
            return false;
        }
        $message = "ICON 2026\n\n"
            . "Dear {$participant->full_name},\n\n"
            . "Congratulations! Your paper has been accepted.\n\n"
            . "Submission: {$submission->submission_code}\n"
            . "Title: {$submission->title}\n\n"
            . "Please log in to the ICON 2026 Participant Portal and upload your final camera-ready version.\n\n"
            . "Regards,\n"
            . "ICON 2026 Secretariat\n"
            . "Universitas Bhamada Slawi";
        return $this->send(
            $participant->phone,
            $message
        );
    }
    public function sendCameraReadyApproved($participant, $submission): bool
    {
        if (!$participant?->phone) {
            return false;
        }
        $message = "ICON 2026\n\n"
            . "Dear {$participant->full_name},\n\n"
            . "Your camera-ready paper has been approved successfully.\n\n"
            . "Submission: {$submission->submission_code}\n"
            . "Title: {$submission->title}\n"
            . "Status: Published\n\n"
            . "Congratulations on the successful publication of your paper.\n\n"
            . "Regards,\n"
            . "ICON 2026 Secretariat\n"
            . "Universitas Bhamada Slawi";
        return $this->send(
            $participant->phone,
            $message
        );
    }
}
