<?php

namespace App\Services;

use App\Models\Certificate;
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
            'presenter_certificate' => false,
        ];

        $reasons = [];

        /*
        |--------------------------------------------------------------------------
        | Legacy Camera Ready workflow
        |--------------------------------------------------------------------------
        */

        if (
            $submission->submission_stage === 'full_paper'
            && $submission->status === 'camera_ready'
        ) {
            $checks['workflow'] = true;

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

            $checks['video'] =
                !empty($submission->video_url)
                && !empty($submission->video_submitted_at);

            if (!$checks['video']) {
                $reasons[] =
                    'The presentation video has not been submitted.';
            }

            $participant =
                $submission->participant;

            $checks['presenter'] =
                $participant
                && $participant->registration_status === 'confirmed'
                && $participant->registrationType?->category === 'presenter';

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

            $attendance =
                $participant
                ? ConferenceAttendance::query()
                    ->where(
                        'conference_id',
                        $submission->conference_id
                    )
                    ->where(
                        'participant_id',
                        $submission->participant_id
                    )
                    ->where(
                        'attendance_status',
                        'verified'
                    )
                    ->first()
                : null;

            $checks['attendance'] =
                $attendance !== null;

            if (!$checks['attendance']) {
                $reasons[] =
                    'Conference attendance has not been verified.';
            }

            return [
                'eligible' => empty($reasons),
                'checks' => $checks,
                'reasons' => $reasons,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Active workflow
        |--------------------------------------------------------------------------
        |
        | Full Paper Accepted
        | -> Presentation Video
        | -> Conference Attendance
        | -> Presenter Certificate
        | -> Publication Recommendation
        |
        */

        $checks['workflow'] =
            $submission->submission_stage === 'full_paper'
            && $submission->status === 'accepted';

        if (!$checks['workflow']) {
            $reasons[] =
                'The submission must be an accepted full paper.';
        }

        $checks['video'] =
            !empty($submission->video_url)
            && !empty($submission->video_submitted_at);

        if (!$checks['video']) {
            $reasons[] =
                'The presentation video has not been submitted.';
        }

        $participant =
            $submission->participant;

        $checks['presenter'] =
            $participant
            && $participant->registration_status === 'confirmed'
            && $participant->registrationType?->category === 'presenter';

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

        $attendance =
            $participant
            ? ConferenceAttendance::query()
                ->where(
                    'conference_id',
                    $submission->conference_id
                )
                ->where(
                    'participant_id',
                    $submission->participant_id
                )
                ->where(
                    'attendance_status',
                    'verified'
                )
                ->first()
            : null;

        $checks['attendance'] =
            $attendance !== null;

        if (!$checks['attendance']) {
            $reasons[] =
                'Conference attendance has not been verified.';
        }

        $checks['presenter_certificate'] =
            $participant
            && Certificate::query()
                ->where(
                    'participant_id',
                    $participant->id
                )
                ->where(
                    'conference_id',
                    $submission->conference_id
                )
                ->where(
                    'submission_id',
                    $submission->id
                )
                ->where(
                    'type',
                    'presenter'
                )
                ->exists();

        if (!$checks['presenter_certificate']) {
            $reasons[] =
                'The presenter certificate has not been generated.';
        }

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
