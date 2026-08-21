<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $reviewer = Auth::user()
            ->reviewers()
            ->where('is_active', true)
            ->latest()
            ->first();

        if (!$reviewer) {
            abort(403, 'Reviewer account is not active.');
        }

        $reviews = Review::with([
            'submission.conference',
            'submission.topic',
        ])
            ->where('reviewer_id', $reviewer->id)
            ->latest()
            ->paginate(15);

        return view('reviewer.reviews.index', compact('reviewer', 'reviews'));
    }

    public function show(Review $review)
    {
        $reviewer = Auth::user()
            ->reviewers()
            ->where('is_active', true)
            ->latest()
            ->first();

        if (!$reviewer) {
            abort(403, 'Reviewer account is not active.');
        }

        if ($review->reviewer_id !== $reviewer->id) {
            abort(403);
        }

        $review->load([
            'submission.conference',
            'submission.topic',
            'submission.authors',
            'reviewer.user',
        ]);

        return view('reviewer.reviews.show', compact('review'));
    }

    public function edit(Review $review)
    {
        $reviewer = Auth::user()
            ->reviewers()
            ->where(
                'is_active',
                true
            )
            ->latest()
            ->first();

        if (!$reviewer) {
            abort(
                403,
                'Reviewer account is not active.'
            );
        }

        if (
            $review->reviewer_id !==
            $reviewer->id
        ) {
            abort(403);
        }

        $review->load([
            'submission.conference.setting',
            'submission.topic',
            'submission.authors',
            'reviewer.user',
        ]);

        if (
            !$review->submission?->conference?->setting?->review_enabled
            || $review->submission?->conference?->setting?->maintenance_mode
        ) {
            return redirect()
                ->route(
                    'reviewer.reviews.show',
                    $review
                )
                ->with(
                    'error',
                    'Review workflow is currently disabled.'
                );
        }

        if ($review->reviewed_at) {
            return redirect()
                ->route(
                    'reviewer.reviews.show',
                    $review
                )
                ->with(
                    'error',
                    'This review has already been submitted.'
                );
        }

        return view(
            'reviewer.reviews.edit',
            compact('review')
        );
    }

    public function update(
        \App\Http\Requests\Reviewer\ReviewRequest $request,
        Review $review
    ) {
        $reviewer = Auth::user()
            ->reviewers()
            ->where('is_active', true)
            ->latest()
            ->first();

        if (!$reviewer) {
            abort(
                403,
                'Reviewer account is not active.'
            );
        }

        if ($review->reviewer_id !== $reviewer->id) {
            abort(403);
        }

        $review->load([
            'submission.conference.setting',
        ]);

        if (
            !$review->submission?->conference?->setting?->review_enabled
            || $review->submission?->conference?->setting?->maintenance_mode
        ) {
            return redirect()
                ->route(
                    'reviewer.reviews.show',
                    $review
                )
                ->with(
                    'error',
                    'Review workflow is currently disabled.'
                );
        }

        if ($review->reviewed_at) {
            return redirect()
                ->route(
                    'reviewer.reviews.show',
                    $review
                )
                ->with(
                    'error',
                    'This review has already been submitted.'
                );
        }

        $validated = $request->validated();

        $review->update([
            'score' => $validated['score'],
            'comment' => $validated['comment'],
            'recommendation' => $validated['recommendation'],
            'reviewed_at' => now(),
        ]);

        $submission = $review->submission;

        $this->updateSubmissionStatus(
            $submission
        );

        return redirect()
            ->route(
                'reviewer.reviews.show',
                $review
            )
            ->with(
                'success',
                'Review submitted successfully.'
            );
    }

    private function updateSubmissionStatus(
        Submission $submission
    ): void {
        $submission->load([
            'reviews',
        ]);

        $reviews = $submission->reviews;

        if ($reviews->isEmpty()) {
            return;
        }

        // Ambil review round terbaru
        $currentRound = $reviews
            ->max('review_round');

        $currentReviews = $reviews->where(
            'review_round',
            $currentRound
        );

        // Masih ada reviewer yang belum submit
        $hasPendingReview = $currentReviews->contains(
            function ($review) {
                return is_null(
                    $review->reviewed_at
                );
            }
        );

        if ($hasPendingReview) {

            $submission->update([
                'status' => 'under_review',
            ]);

            return;
        }

        // Ada reviewer yang menolak
        $hasReject = $currentReviews->contains(
            function ($review) {
                return $review->recommendation === 'reject';
            }
        );

        if ($hasReject) {

            $submission->update([
                'status' => 'rejected',
            ]);

            return;
        }

        // Ada reviewer meminta revisi
        $hasRevision = $currentReviews->contains(
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
        );

        if ($hasRevision) {

            $submission->update([
                'status' => 'revision',
            ]);

            return;
        }

        // Semua reviewer menerima
        $allAccepted = $currentReviews->every(
            function ($review) {
                return $review->recommendation === 'accept';
            }
        );

        if ($allAccepted) {

            $submission->update([
                'status' => 'accepted',
            ]);
        }
    }
}
