<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Submission;
use App\Mail\SubmissionStatusMail;
use App\Jobs\SendRevisionRequiredWhatsApp;
use App\Jobs\SendSubmissionAcceptedWhatsApp;
use App\Notifications\ConferenceNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    public function downloadPaper(Review $review)
    {
        $reviewer = Auth::user()
            ->reviewers()
            ->where('is_active', true)
            ->latest()
            ->first();

        if (!$reviewer) {
            abort(403);
        }

        if ($review->reviewer_id !== $reviewer->id) {
            abort(403);
        }

        $review->load('submission');

        $submission = $review->submission;

        abort_unless(
            $submission?->paper_file
                && Storage::disk('local')->exists(
                    $submission->paper_file
                ),
            404
        );

        return Storage::disk('local')->download(
            $submission->paper_file,
            basename($submission->paper_file)
        );
    }

    private function updateSubmissionStatus(
        Submission $submission
    ): void {
        $submission->load([
            'reviews',
        ]);

        $reviewStage =
            $submission->submission_stage;

        $reviews =
            $submission->reviews
            ->where(
                'review_stage',
                $reviewStage
            );

        if ($reviews->isEmpty()) {
            return;
        }

        $currentRound =
            $reviews->max('review_round');

        $currentReviews =
            $reviews->where(
                'review_round',
                $currentRound
            );

        if ($currentReviews->isEmpty()) {
            return;
        }

        /*
    |--------------------------------------------------------------------------
    | Pending Review
    |--------------------------------------------------------------------------
    */

        $hasPendingReview =
            $currentReviews->contains(
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

        /*
    |--------------------------------------------------------------------------
    | Rejected
    |--------------------------------------------------------------------------
    */

        $hasReject =
            $currentReviews->contains(
                function ($review) {
                    return $review->recommendation === 'reject';
                }
            );

        if ($hasReject) {
            $submission->update([
                'status' => 'rejected',
            ]);

            $submission->load([
                'participant',
            ]);

            if ($submission->participant?->email) {
                Mail::to(
                    $submission->participant->email
                )->queue(
                    new SubmissionStatusMail(
                        $submission,
                        $reviewStage === 'abstract'
                            ? 'We are sorry to inform you that your abstract has not been accepted.'
                            : 'We are sorry to inform you that your full paper has not been accepted for publication.'
                    )
                );
            }

            return;
        }

        /*
    |--------------------------------------------------------------------------
    | Revision Required
    |--------------------------------------------------------------------------
    */

        $hasRevision =
            $currentReviews->contains(
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

            $submission->load([
                'participant',
            ]);

            if ($submission->participant?->email) {
                Mail::to(
                    $submission->participant->email
                )->queue(
                    new SubmissionStatusMail(
                        $submission,
                        $reviewStage === 'abstract'
                            ? 'Your abstract requires revision based on the reviewer feedback. Please log in to the participant portal and submit the revised abstract.'
                            : 'Your full paper requires revision based on the reviewer feedback. Please log in to the participant portal and upload your revised manuscript.'
                    )
                );
            }

            if ($submission->participant?->phone) {
                SendRevisionRequiredWhatsApp::dispatch(
                    $submission->participant->id,
                    $submission->id
                );
            }

            if ($submission->participant?->user) {
                $submission->participant->user->notify(
                    new ConferenceNotification(
                        $reviewStage === 'abstract'
                            ? 'Abstract Revision Required'
                            : 'Paper Revision Required',

                        $reviewStage === 'abstract'
                            ? 'Your abstract requires revision based on the reviewer feedback. Please review the feedback and submit your revised abstract.'
                            : 'Your full paper requires revision based on the reviewer feedback. Please review the feedback and upload your revised manuscript.',

                        'Upload Revision',

                        route(
                            'participant.submissions.revision',
                            $submission
                        ),

                        'warning'
                    )
                );
            }

            return;
        }

        /*
    |--------------------------------------------------------------------------
    | All Reviewers Accepted
    |--------------------------------------------------------------------------
    */

        $allAccepted =
            $currentReviews->every(
                function ($review) {
                    return $review->recommendation === 'accept';
                }
            );

        if (
            $currentReviews->isNotEmpty()
            && $allAccepted
        ) {
            $submission->update([
                'status' => 'accepted',
            ]);

            $submission->load([
                'participant',
            ]);

            if ($submission->participant?->email) {
                Mail::to(
                    $submission->participant->email
                )->queue(
                    new SubmissionStatusMail(
                        $submission,
                        $reviewStage === 'abstract'
                            ? 'Congratulations! Your abstract has been accepted. Please log in to the participant portal to submit your full paper.'
                            : 'Congratulations! Your full paper has been accepted. Please log in to the participant portal to continue with the next stage.'
                    )
                );
            }

            if (
                $reviewStage === 'full_paper'
                && $submission->participant?->phone
            ) {
                SendSubmissionAcceptedWhatsApp::dispatch(
                    $submission->participant->id,
                    $submission->id
                );
            }

            if ($submission->participant?->user) {

                if ($reviewStage === 'abstract') {

                    $submission->participant->user->notify(
                        new ConferenceNotification(
                            'Abstract Accepted',
                            'Congratulations! Your abstract has been accepted. You can now submit your full paper.',
                            'Submit Full Paper',
                            route(
                                'participant.submissions.full-paper',
                                $submission
                            ),
                            'success'
                        )
                    );
                } else {

                    $submission->participant->user->notify(
                        new ConferenceNotification(
                            'Full Paper Accepted',
                            'Congratulations! Your full paper has been accepted. Your Letter of Acceptance is now available.',
                            'View LOA',
                            route(
                                'participant.submissions.loa',
                                $submission
                            ),
                            'success'
                        )
                    );
                }
            }
        }
    }
}
