<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Conference;
use App\Models\Participant;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Submission;
use App\Models\Topic;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $conferenceId = $request->integer('conference_id');
        $topicId = $request->integer('topic_id');

        $conferences = Conference::orderByDesc('year')
            ->orderBy('name')
            ->get();

        $topicsQuery = Topic::query()
            ->orderBy('conference_id')
            ->orderBy('sort_order')
            ->orderBy('name');

        if ($conferenceId) {
            $topicsQuery->where(
                'conference_id',
                $conferenceId
            );
        }

        $topics = $topicsQuery->get();

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

        $presenterVideoQuery = Submission::query()
            ->with([
                'topic',
                'participant.registrationType',
            ])
            ->where(
                'submission_stage',
                'full_paper'
            )
            ->where(
                'status',
                'accepted'
            )
            ->whereNotNull('video_url')
            ->where(
                'video_url',
                '!=',
                ''
            )
            ->whereNotNull('video_submitted_at')
            ->whereHas(
                'participant',
                function ($query) {
                    $query
                        ->where(
                            'registration_status',
                            'confirmed'
                        )
                        ->whereHas(
                            'registrationType',
                            function ($query) {
                                $query->where(
                                    'category',
                                    'presenter'
                                );
                            }
                        );
                }
            );

        if ($conferenceId) {
            $presenterVideoQuery->where(
                'conference_id',
                $conferenceId
            );
        }

        if ($topicId) {
            $presenterVideoQuery->where(
                'topic_id',
                $topicId
            );
        }

        $presenterVideoSubmissions = $presenterVideoQuery
            ->orderByDesc('video_submitted_at')
            ->get();

        return view(
            'admin.reports.index',
            compact(
                'conferences',
                'conferenceId',
                'topics',
                'topicId',
                'statistics',
                'presenterVideoSubmissions'
            )
        );
    }
}
