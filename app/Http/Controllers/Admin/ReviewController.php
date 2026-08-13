<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Models\Reviewer;
use App\Models\Review;
use App\Models\Submission;

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

        $assignedReviewerIds = Review::where(
            'submission_id',
            $submission->id
        )
            ->pluck('reviewer_id');

        $reviewers = Reviewer::with('user')
            ->where('conference_id', $submission->conference_id)
            ->where('is_active', true)
            ->whereNotIn('id', $assignedReviewerIds)
            ->orderBy('id')
            ->get();

        return view('admin.reviews.create', compact('submission', 'reviewers'));
    }

    public function storeForSubmission(
        ReviewRequest $request,
        Submission $submission
    ) {
        Review::create([
            'submission_id' => $submission->id,
            'reviewer_id' => $request->validated('reviewer_id'),
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

        $this->updateSubmissionStatus(
            $review->submission()->first()
        );

        return redirect()
            ->route('admin.reviews.show', $review)
            ->with(
                'success',
                'Review submitted successfully.'
            );
    }

    private function updateSubmissionStatus(Submission $submission): void
    {
        $submission->load('reviews');

        $reviews = $submission->reviews;

        if ($reviews->isEmpty()) {
            return;
        }

        $hasPendingReview = $reviews->contains(function ($review) {
            return is_null($review->reviewed_at);
        });

        if ($hasPendingReview) {
            $submission->update([
                'status' => 'under_review',
            ]);

            return;
        }

        if ($reviews->contains(function ($review) {
            return $review->recommendation === 'reject';
        })) {
            $submission->update([
                'status' => 'rejected',
            ]);

            return;
        }

        if ($reviews->contains(function ($review) {
            return in_array(
                $review->recommendation,
                [
                    'minor_revision',
                    'major_revision',
                ],
                true
            );
        })) {
            $submission->update([
                'status' => 'revision',
            ]);

            return;
        }

        $allAccepted = $reviews->every(function ($review) {
            return $review->recommendation === 'accept';
        });

        if ($allAccepted) {
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
}
