<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Conference;
use App\Models\Participant;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Submission;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $conferenceId = $request->integer('conference_id');

        $conferences = Conference::orderByDesc('year')
            ->orderBy('name')
            ->get();

        $participantQuery = Participant::query();

        $paymentQuery = Payment::query();

        $submissionQuery = Submission::query();

        $reviewQuery = Review::query();

        $certificateQuery = Certificate::query();

        if ($conferenceId) {

            $participantQuery->where(
                'conference_id',
                $conferenceId
            );

            $paymentQuery->whereHas(
                'participant',
                function ($query) use ($conferenceId) {
                    $query->where(
                        'conference_id',
                        $conferenceId
                    );
                }
            );

            $submissionQuery->where(
                'conference_id',
                $conferenceId
            );

            $reviewQuery->whereHas(
                'submission',
                function ($query) use ($conferenceId) {
                    $query->where(
                        'conference_id',
                        $conferenceId
                    );
                }
            );

            $certificateQuery->where(
                'conference_id',
                $conferenceId
            );
        }

        $statistics = [
            'participants' =>
            $participantQuery->count(),

            'confirmed_participants' => (clone $participantQuery)
                ->where(
                    'registration_status',
                    'confirmed'
                )
                ->count(),

            'pending_payments' => (clone $paymentQuery)
                ->where(
                    'status',
                    'pending'
                )
                ->count(),

            'verified_payments' => (clone $paymentQuery)
                ->where(
                    'status',
                    'verified'
                )
                ->count(),

            'submissions' =>
            $submissionQuery->count(),

            'under_review' => (clone $submissionQuery)
                ->where(
                    'status',
                    'under_review'
                )
                ->count(),

            'revision' => (clone $submissionQuery)
                ->where(
                    'status',
                    'revision'
                )
                ->count(),

            'accepted' => (clone $submissionQuery)
                ->where(
                    'status',
                    'accepted'
                )
                ->count(),

            'published' => (clone $submissionQuery)
                ->where(
                    'status',
                    'published'
                )
                ->count(),

            'reviews' =>
            $reviewQuery->count(),

            'completed_reviews' => (clone $reviewQuery)
                ->whereNotNull('reviewed_at')
                ->count(),

            'certificates' =>
            $certificateQuery->count(),
        ];

        return view(
            'admin.reports.index',
            compact(
                'conferences',
                'conferenceId',
                'statistics'
            )
        );
    }
}
