<?php

namespace App\Services;

use App\Models\ConferenceAttendance;
use App\Models\Participant;
use App\Models\Reviewer;
use App\Models\Submission;

class CertificateEligibilityService
{
    public function evaluate(
        Participant $participant,
        string $type,
        ?Submission $submission = null
    ): array {
        $participant->loadMissing([
            'conference',
        ]);

        $checks = [];
        $reasons = [];

        $attendanceVerified = ConferenceAttendance::query()
            ->where(
                'conference_id',
                $participant->conference_id
            )
            ->where(
                'participant_id',
                $participant->id
            )
            ->where(
                'attendance_status',
                'verified'
            )
            ->exists();

        switch ($type) {
            case 'participant':
                $checks['registration_confirmed'] =
                    $participant->registration_status === 'confirmed';

                $checks['attendance_verified'] =
                    $attendanceVerified;

                if (!$checks['registration_confirmed']) {
                    $reasons[] =
                        'Participant registration must be confirmed.';
                }

                if (!$checks['attendance_verified']) {
                    $reasons[] =
                        'Conference attendance must be verified.';
                }

                break;

            case 'presenter':
                $checks['published_submission'] =
                    $submission instanceof Submission
                    && $submission->participant_id === $participant->id
                    && $submission->conference_id === $participant->conference_id
                    && $submission->status === 'published';

                $checks['attendance_verified'] =
                    $attendanceVerified;

                if (!$checks['published_submission']) {
                    $reasons[] =
                        'A presenter certificate requires a published submission belonging to the participant.';
                }

                if (!$checks['attendance_verified']) {
                    $reasons[] =
                        'Conference attendance must be verified.';
                }

                break;

            case 'speaker':
                $checks['speaker_registration'] =
                    $participant->participant_type === 'speaker'
                    && $participant->registration_status === 'confirmed';

                $checks['attendance_verified'] =
                    $attendanceVerified;

                if (!$checks['speaker_registration']) {
                    $reasons[] =
                        'The participant must be a confirmed speaker registration.';
                }

                if (!$checks['attendance_verified']) {
                    $reasons[] =
                        'Conference attendance must be verified.';
                }

                break;

            case 'committee':
                $checks['committee_registration'] =
                    $participant->participant_type === 'committee'
                    && $participant->registration_status === 'confirmed';

                $checks['attendance_verified'] =
                    $attendanceVerified;

                if (!$checks['committee_registration']) {
                    $reasons[] =
                        'The participant must be a confirmed committee registration.';
                }

                if (!$checks['attendance_verified']) {
                    $reasons[] =
                        'Conference attendance must be verified.';
                }

                break;

            case 'reviewer':
                $checks['reviewer_assignment'] = false;
                $checks['reviews_completed'] = false;

                if (!$participant->user_id) {
                    $reasons[] =
                        'The reviewer participant must be linked to a user.';
                    break;
                }

                $reviewer = Reviewer::query()
                    ->where(
                        'conference_id',
                        $participant->conference_id
                    )
                    ->where(
                        'user_id',
                        $participant->user_id
                    )
                    ->first();

                if (!$reviewer) {
                    $reasons[] =
                        'No reviewer assignment was found for this participant.';
                    break;
                }

                $totalReviews = $reviewer
                    ->reviews()
                    ->count();

                $completedReviews = $reviewer
                    ->reviews()
                    ->whereNotNull('reviewed_at')
                    ->count();

                $checks['reviewer_assignment'] =
                    $totalReviews > 0;

                $checks['reviews_completed'] =
                    $totalReviews > 0
                    && $completedReviews === $totalReviews;

                if (!$checks['reviewer_assignment']) {
                    $reasons[] =
                        'The reviewer must have at least one assigned review.';
                }

                if (
                    $checks['reviewer_assignment']
                    && !$checks['reviews_completed']
                ) {
                    $reasons[] =
                        'All reviews assigned to the reviewer must be completed.';
                }

                break;

            default:
                $reasons[] =
                    'Unsupported certificate type.';
                break;
        }

        return [
            'eligible' => empty($reasons),
            'checks' => $checks,
            'reasons' => $reasons,
        ];
    }

    public function isEligible(
        Participant $participant,
        string $type,
        ?Submission $submission = null,
    ): bool {
        return $this->evaluate(
            $participant,
            $type,
            $submission
        )['eligible'];
    }
}
