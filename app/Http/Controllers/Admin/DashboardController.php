<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Conference;
use App\Models\Participant;
use App\Models\Payment;
use App\Models\Submission;
use App\Models\Topic;

class DashboardController extends Controller
{
    public function index()
    {
        $conferenceCount = Conference::count();

        $topicCount = Topic::count();

        $activeConference = Conference::with('setting')
            ->whereHas('setting', function ($query) {
                $query->where('is_active', true);
            })
            ->latest('year')
            ->first();

        if (!$activeConference) {
            $activeConference = Conference::latest('year')->first();
        }

        $latestTopics = Topic::with('conference')
            ->latest()
            ->take(5)
            ->get();

        $participantCount = Participant::count();

        $confirmedParticipantCount = Participant::where(
            'registration_status',
            'confirmed'
        )->count();

        $pendingPaymentCount = Payment::where(
            'status',
            'pending'
        )->count();

        $submissionCount = Submission::count();

        $underReviewCount = Submission::where(
            'status',
            'under_review'
        )->count();

        $revisionCount = Submission::where(
            'status',
            'revision'
        )->count();

        $acceptedCount = Submission::where(
            'status',
            'accepted'
        )->count();

        $publishedCount = Submission::where(
            'status',
            'published'
        )->count();

        $certificateCount = Certificate::count();

        return view('admin.dashboard', compact(
            'conferenceCount',
            'topicCount',
            'activeConference',
            'latestTopics',

            'participantCount',
            'confirmedParticipantCount',
            'pendingPaymentCount',

            'submissionCount',
            'underReviewCount',
            'revisionCount',
            'acceptedCount',
            'publishedCount',

            'certificateCount',
        ));
    }
}
