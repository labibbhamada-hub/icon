<?php

namespace App\Services;

use App\Models\ConferenceAttendance;
use App\Models\Submission;
use Illuminate\Support\Facades\Storage;

class PublicationEligibilityService
{
    public function evaluate(
        Submission $submission
    ): array {
        $submission->loadMissing([
            'participant.registrationType',
        ]);

        $checks = [
            'workflow' => false,
            'camera_ready' => false,
            'video' => false,
            'attendance' => false,
            'presenter' => false,
        ];

        $reasons = [];

        /*
        |--------------------------------------------------------------------------
        | Workflow
        |--------------------------------------------------------------------------
        */

        $isFullPaper =
            $submission->submission_stage === 'full_paper';

        $isCameraReady =
            $submission->status === 'camera_ready';

        $checks['workflow'] =
            $isFullPaper
            && $isCameraReady;

        if (!$isFullPaper) {
            $reasons[] =
                'The submission must be a full paper.';
        }

        if (!$isCameraReady) {
            $reasons[] =
                'The submission must be in Camera Ready status.';
        }

        /*
        |--------------------------------------------------------------------------
        | Camera Ready
        |--------------------------------------------------------------------------
        */

        $hasCameraReadyFile =
            !empty($submission->camera_ready_file);

        $cameraReadyFileExists =
            $hasCameraReadyFile
            && Storage::disk('local')->exists(
                $submission->camera_ready_file
            );

        $checks['camera_ready'] =
            $submission->camera_ready_status === 'submitted'
            && $hasCameraReadyFile
            && $cameraReadyFileExists;

        if (
            $submission->camera_ready_status !== 'submitted'
        ) {
            $reasons[] =
                'The camera-ready submission is not ready for publication approval.';
        }

        if (!$hasCameraReadyFile) {
            $reasons[] =
                'The camera-ready file has not been submitted.';
        } elseif (!$cameraReadyFileExists) {
            $reasons[] =
                'The camera-ready file could not be found on the server.';
        }

        /*
        |--------------------------------------------------------------------------
        | Presentation Video
        |--------------------------------------------------------------------------
        */

        $checks['video'] =
            !empty($submission->video_url)
            && !empty($submission->video_submitted_at);

        if (!$checks['video']) {
            $reasons[] =
                'The presentation video has not been submitted.';
        }

        /*
        |--------------------------------------------------------------------------
        | Presenter
        |--------------------------------------------------------------------------
        */

        $participant =
            $submission->participant;

        $hasValidPresenterParticipant =
            $participant
            && $participant->registration_status === 'confirmed'
            && $participant->registrationType?->category === 'presenter';

        $checks['presenter'] =
            $hasValidPresenterParticipant;

        if (!$participant) {
            $reasons[] =
                'The submission participant could not be found.';
        } elseif (
            $participant->registration_status !== 'confirmed'
            || $participant->registrationType?->category !== 'presenter'
        ) {
            $reasons[] =
                'The submission participant is not a confirmed presenter.';
        }

        /*
        |--------------------------------------------------------------------------
        | Attendance
        |--------------------------------------------------------------------------
        */

        $attendance = null;

        if ($participant) {
            $attendance =
                ConferenceAttendance::query()
                ->where(
                    'conference_id',
                    $submission->conference_id
                )
                ->where(
                    'participant_id',
                    $submission->participant_id
                )
                ->first();
        }

        $checks['attendance'] =
            $attendance?->attendance_status === 'verified';

        if (!$checks['attendance']) {
            $reasons[] =
                'Conference attendance has not been verified.';
        }

        /*
        |--------------------------------------------------------------------------
        | Result
        |--------------------------------------------------------------------------
        */

        return [
            'eligible' => empty($reasons),
            'checks' => $checks,
            'reasons' => $reasons,
        ];
    }

    public function isEligible(
        Submission $submission
    ): bool {
        return $this->evaluate($submission)['eligible'];
    }
}
