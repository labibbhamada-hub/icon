<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\Review;
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
            ->where('is_active', true)
            ->latest()
            ->first();

        if (!$reviewer) {
            abort(403, 'Reviewer account is not active.');
        }

        if ($review->reviewer_id !== $reviewer->id) {
            abort(403);
        }

        if ($review->reviewed_at) {
            return redirect()
                ->route('reviewer.reviews.show', $review)
                ->with(
                    'error',
                    'This review has already been submitted.'
                );
        }

        $review->load([
            'submission.conference',
            'submission.topic',
            'submission.authors',
            'reviewer.user',
        ]);

        return view('reviewer.reviews.edit', compact('review'));
    }

    public function update(\App\Http\Requests\Reviewer\ReviewRequest $request, Review $review)
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

        if ($review->reviewed_at) {
            return redirect()
                ->route('reviewer.reviews.show', $review)
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

        return redirect()
            ->route('reviewer.reviews.show', $review)
            ->with(
                'success',
                'Review submitted successfully.'
            );
    }
}
