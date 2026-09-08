<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Conference;
use App\Models\Participant;
use App\Models\Payment;
use App\Models\Submission;
use App\Models\Speaker;
use App\Models\Topic;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Conferences
        |--------------------------------------------------------------------------
        */

        $conferenceCount = Conference::count();

        $activeConference = Conference::with('setting')
            ->whereHas('setting', function ($query) {
                $query->where('is_active', true);
            })
            ->latest('year')
            ->first();

        if (!$activeConference) {
            $activeConference = Conference::latest('year')->first();
        }

        /*
        |--------------------------------------------------------------------------
        | Conference-scoped statistics
        |--------------------------------------------------------------------------
        */

        if ($activeConference) {

            $conferenceId = $activeConference->id;

            $topicCount = Topic::where(
                'conference_id',
                $conferenceId
            )->count();

            $speakerCount = Speaker::where(
                'conference_id',
                $conferenceId
            )->count();

            $participantCount = Participant::where(
                'conference_id',
                $conferenceId
            )->count();

            $confirmedParticipantCount =
                Participant::where(
                    'conference_id',
                    $conferenceId
                )
                ->where(
                    'registration_status',
                    'confirmed'
                )
                ->count();

            $pendingPaymentCount = Payment::whereHas(
                'participant',
                function ($query) use ($conferenceId) {
                    $query->where(
                        'conference_id',
                        $conferenceId
                    );
                }
            )
                ->where(
                    'status',
                    'pending'
                )
                ->count();

            $submissionCount = Submission::where(
                'conference_id',
                $conferenceId
            )->count();

            $underReviewCount = Submission::where(
                'conference_id',
                $conferenceId
            )
                ->where(
                    'status',
                    'under_review'
                )
                ->count();

            $revisionCount = Submission::where(
                'conference_id',
                $conferenceId
            )
                ->where(
                    'status',
                    'revision'
                )
                ->count();

            $acceptedCount = Submission::where(
                'conference_id',
                $conferenceId
            )
                ->where(
                    'status',
                    'accepted'
                )
                ->count();

            $publishedCount = Submission::where(
                'conference_id',
                $conferenceId
            )
                ->where(
                    'status',
                    'published'
                )
                ->count();

            $certificateCount = Certificate::where(
                'conference_id',
                $conferenceId
            )->count();

            $latestTopics = Topic::with('conference')
                ->where(
                    'conference_id',
                    $conferenceId
                )
                ->latest()
                ->take(5)
                ->get();
        } else {

            $topicCount = 0;
            $speakerCount = 0;
            $participantCount = 0;
            $confirmedParticipantCount = 0;
            $pendingPaymentCount = 0;
            $submissionCount = 0;
            $underReviewCount = 0;
            $revisionCount = 0;
            $acceptedCount = 0;
            $publishedCount = 0;
            $certificateCount = 0;
            $latestTopics = collect();
        }

        return view(
            'admin.dashboard',
            compact(
                'conferenceCount',
                'activeConference',
                'topicCount',
                'speakerCount',
                'participantCount',
                'confirmedParticipantCount',
                'pendingPaymentCount',
                'submissionCount',
                'underReviewCount',
                'revisionCount',
                'acceptedCount',
                'publishedCount',
                'certificateCount',
                'latestTopics',
            )
        );
    }
}
