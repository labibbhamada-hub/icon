<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Models\Reviewer;
use App\Models\Review;
use App\Models\Submission;
use App\Mail\SubmissionStatusMail;
use App\Exports\ReviewsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with([
            'submission',
            'reviewer.user',
        ])
            ->latest()
            ->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function createForSubmission(Submission $submission)
    {
        $submission->load([
            'conference',
            'participant',
            'topic',
            'authors',
        ]);

        $reviewStage = $submission->submission_stage;

        $currentRound = Review::where(
            'submission_id',
            $submission->id
        )
            ->where(
                'review_stage',
                $reviewStage
            )
            ->max('review_round');

        $currentRound = $currentRound ?: 1;

        /*
    |--------------------------------------------------------------------------
    | Reviewer already assigned in the current stage + round
    |--------------------------------------------------------------------------
    */

        $assignedReviewerIds = Review::where(
            'submission_id',
            $submission->id
        )
            ->where(
                'review_stage',
                $reviewStage
            )
            ->where(
                'review_round',
                $currentRound
            )
            ->pluck('reviewer_id');

        /*
    |--------------------------------------------------------------------------
    | Reviewer already used in another stage
    |--------------------------------------------------------------------------
    */

        $previousStageReviewerIds = Review::where(
            'submission_id',
            $submission->id
        )
            ->where(
                'review_stage',
                '!=',
                $reviewStage
            )
            ->pluck('reviewer_id');

        /*
    |--------------------------------------------------------------------------
    | Available Reviewers
    |--------------------------------------------------------------------------
    */

        $excludedReviewerIds = $assignedReviewerIds
            ->merge($previousStageReviewerIds)
            ->unique();

        $reviewers = Reviewer::with('user')
            ->where(
                'conference_id',
                $submission->conference_id
            )
            ->where(
                'is_active',
                true
            )
            ->whereNotIn(
                'id',
                $excludedReviewerIds
            )
            ->orderBy('id')
            ->get();

        return view(
            'admin.reviews.create',
            compact(
                'submission',
                'reviewers',
                'currentRound',
                'reviewStage'
            )
        );
    }

    public function storeForSubmission(
        ReviewRequest $request,
        Submission $submission
    ) {
        $submission->load([
            'conference.setting',
        ]);

        if (
            !$submission->conference?->setting?->review_enabled
            || $submission->conference?->setting?->maintenance_mode
        ) {
            return back()
                ->with(
                    'error',
                    'Review workflow is currently disabled for this conference.'
                );
        }

        $reviewStage = $submission->submission_stage;

        $reviewerId = $request->validated('reviewer_id');

        /*
    |--------------------------------------------------------------------------
    | Prevent reviewer reuse across different submission stages
    |--------------------------------------------------------------------------
    */

        $usedInPreviousStage = Review::where(
            'submission_id',
            $submission->id
        )
            ->where(
                'reviewer_id',
                $reviewerId
            )
            ->where(
                'review_stage',
                '!=',
                $reviewStage
            )
            ->exists();

        if ($usedInPreviousStage) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'This reviewer has already reviewed this submission in another stage and cannot be assigned again.'
                );
        }

        /*
    |--------------------------------------------------------------------------
    | Current review round
    |--------------------------------------------------------------------------
    */

        $currentRound = Review::where(
            'submission_id',
            $submission->id
        )
            ->where(
                'review_stage',
                $reviewStage
            )
            ->max('review_round');

        $currentRound = $currentRound ?: 1;

        /*
    |--------------------------------------------------------------------------
    | Prevent duplicate assignment in current stage + round
    |--------------------------------------------------------------------------
    */

        $alreadyAssigned = Review::where(
            'submission_id',
            $submission->id
        )
            ->where(
                'reviewer_id',
                $reviewerId
            )
            ->where(
                'review_stage',
                $reviewStage
            )
            ->where(
                'review_round',
                $currentRound
            )
            ->exists();

        if ($alreadyAssigned) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'This reviewer has already been assigned in the current review stage and round.'
                );
        }

        Review::create([
            'submission_id' => $submission->id,
            'reviewer_id' => $reviewerId,
            'review_stage' => $reviewStage,
            'review_round' => $currentRound,
            'score' => null,
            'comment' => null,
            'recommendation' => null,
            'reviewed_at' => null,
        ]);

        return redirect()
            ->route(
                'admin.submissions.show',
                $submission
            )
            ->with(
                'success',
                'Reviewer assigned successfully.'
            );
    }

    public function create()
    {
        //
    }

    public function store()
    {
        //
    }

    public function show(Review $review)
    {
        $review->load([
            'submission.conference',
            'submission.topic',
            'reviewer.user',
        ]);

        return view('admin.reviews.show', compact('review'));
    }

    public function edit(Review $review)
    {
        $review->load([
            'submission',
            'reviewer.user',
        ]);

        return view('admin.reviews.edit', compact('review'));
    }

    public function update(ReviewRequest $request, Review $review)
    {
        $review->update([
            'score' => $request->validated('score'),
            'comment' => $request->validated('comment'),
            'recommendation' => $request->validated('recommendation'),
            'reviewed_at' => now(),
        ]);

        $submission = $review->submission()->first();

        $oldStatus = $submission->status;

        $this->updateSubmissionStatus($submission);

        $submission->refresh();

        if ($oldStatus !== $submission->status) {
            $message = match ($submission->status) {
                'revision' => 'Your submission requires revision based on the reviewer evaluation.',
                'accepted' => 'Congratulations! Your submission has been accepted.',
                'rejected' => 'Your submission has been rejected based on the review result.',
                default => 'Your submission status has been updated.',
            };
            $submission->load('participant');
            if ($submission->participant?->email) {
                Mail::to(
                    $submission->participant->email
                )->queue(
                    new SubmissionStatusMail(
                        $submission,
                        $message
                    )
                );
            }
        }

        return redirect()
            ->route(
                'admin.reviews.show',
                $review
            )
            ->with(
                'success',
                'Review submitted successfully.'
            );
    }

    private function updateSubmissionStatus(Submission $submission): void
    {
        $submission->load('reviews');

        $reviewStage = $submission->submission_stage;

        $reviews = $submission->reviews
            ->where('review_stage', $reviewStage);

        if ($reviews->isEmpty()) {
            return;
        }

        $currentRound = $reviews->max('review_round');

        $currentReviews = $reviews->where(
            'review_round',
            $currentRound
        );

        if ($currentReviews->isEmpty()) {
            return;
        }

        $hasPendingReview = $currentReviews->contains(
            function ($review) {
                return is_null($review->reviewed_at);
            }
        );

        if ($hasPendingReview) {
            $submission->update([
                'status' => 'under_review',
            ]);

            return;
        }

        if ($currentReviews->contains(
            function ($review) {
                return $review->recommendation === 'reject';
            }
        )) {
            $submission->update([
                'status' => 'rejected',
            ]);

            return;
        }

        if ($currentReviews->contains(
            function ($review) {
                return in_array(
                    $review->recommendation,
                    [
                        'minor_revision',
                        'major_revision',
                    ],
                    true
                );
            }
        )) {
            $submission->update([
                'status' => 'revision',
            ]);

            return;
        }

        if (
            $currentReviews->every(
                function ($review) {
                    return $review->recommendation === 'accept';
                }
            )
        ) {
            $submission->update([
                'status' => 'accepted',
            ]);
        }
    }

    public function destroy(Review $review)
    {
        if ($review->reviewed_at) {
            return back()
                ->with(
                    'error',
                    'A completed review cannot be removed.'
                );
        }

        $review->delete();

        return back()
            ->with(
                'success',
                'Reviewer assignment removed successfully.'
            );
    }

    public function export(Request $request)
    {
        $conferenceId = $request->integer('conference_id');

        $suffix = $conferenceId
            ? '-conference-' . $conferenceId
            : '-all';

        return Excel::download(
            new ReviewsExport($conferenceId),
            'reviews' .
                $suffix .
                '-' .
                now()->format('Y-m-d') .
                '.xlsx'
        );
    }
}
