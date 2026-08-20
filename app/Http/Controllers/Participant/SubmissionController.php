<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Participant\CameraReadyRequest;
use App\Http\Requests\Participant\RevisionRequest;
use App\Http\Requests\Participant\SubmissionRequest;
use App\Models\Participant;
use App\Models\Review;
use App\Models\Submission;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SubmissionController extends Controller
{
    public function index()
    {
        $participantIds = Participant::where(
            'user_id',
            Auth::id()
        )
            ->pluck('id');

        $submissions = Submission::with([
            'conference',
            'topic',
            'authors',
        ])
            ->whereIn(
                'participant_id',
                $participantIds
            )
            ->latest()
            ->paginate(10);

        return view(
            'participant.submissions.index',
            compact('submissions')
        );
    }

    public function create()
    {
        $participants = Participant::with([
            'conference.settings',
            'conference.configuration',
        ])
            ->where(
                'user_id',
                Auth::id()
            )
            ->where(
                'registration_status',
                'confirmed'
            )
            ->whereHas('conference.settings', function ($query) {
                $query
                    ->where('is_active', true)
                    ->where('submission_enabled', true)
                    ->where('maintenance_mode', false);
            })
            ->get();

        if ($participants->isEmpty()) {
            return redirect()
                ->route(
                    'participant.submissions.index'
                )
                ->with(
                    'error',
                    'You do not have any confirmed registration with submissions currently enabled.'
                );
        }

        $participant = $participants->first();

        $topics = Topic::where(
            'conference_id',
            $participant->conference_id
        )
            ->where(
                'is_active',
                true
            )
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view(
            'participant.submissions.create',
            compact(
                'participants',
                'topics'
            )
        );
    }

    public function store(
        SubmissionRequest $request
    ) {
        $data = $request->validated();

        $participant = Participant::with([
            'conference.settings',
            'conference.configuration',
        ])
            ->where(
                'id',
                $data['participant_id']
            )
            ->where(
                'user_id',
                Auth::id()
            )
            ->where(
                'registration_status',
                'confirmed'
            )
            ->firstOrFail();

        if (
            !$participant->conference?->settings?->submission_enabled
            || $participant->conference?->settings?->maintenance_mode
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Submission is currently unavailable for this conference.'
                );
        }

        $topic = Topic::where(
            'id',
            $data['topic_id']
        )
            ->where(
                'conference_id',
                $participant->conference_id
            )
            ->where(
                'is_active',
                true
            )
            ->firstOrFail();

        $submission = DB::transaction(function () use (
            $request,
            $data,
            $participant,
            $topic
        ) {
            $paperFile = $request
                ->file('paper_file')
                ->store(
                    'submissions/papers',
                    'public'
                );

            $submission = Submission::create([
                'conference_id' =>
                $participant->conference_id,

                'participant_id' =>
                $participant->id,

                'topic_id' =>
                $topic->id,

                'submission_code' =>
                $this->generateSubmissionCode(
                    $participant->conference
                ),

                'title' =>
                $data['title'],

                'abstract' =>
                $data['abstract'],

                'keywords' =>
                $data['keywords'],

                'paper_file' =>
                $paperFile,

                'status' =>
                'submitted',

                'submitted_at' =>
                now(),
            ]);

            foreach (
                $data['authors'] as $index => $author
            ) {
                $submission->authors()->create([
                    'name' =>
                    $author['name'],

                    'email' =>
                    $author['email'] ?? null,

                    'institution' =>
                    $author['institution'] ?? null,

                    'department' =>
                    $author['department'] ?? null,

                    'is_corresponding' =>
                    !empty($author['is_corresponding']),

                    'sort_order' =>
                    $author['sort_order'] ??
                        ($index + 1),
                ]);
            }

            return $submission;
        });

        return redirect()
            ->route(
                'participant.submissions.show',
                $submission
            )
            ->with(
                'success',
                'Submission created successfully.'
            );
    }

    public function show(
        Submission $submission
    ) {
        $participant = Participant::where(
            'user_id',
            Auth::id()
        )
            ->where(
                'id',
                $submission->participant_id
            )
            ->first();

        abort_unless(
            $participant,
            403
        );

        $submission->load([
            'conference.settings',
            'topic',
            'authors',
        ]);

        return view(
            'participant.submissions.show',
            compact('submission')
        );
    }

    public function revision(
        Submission $submission
    ) {
        $participant =
            $this->getOwnedSubmissionParticipant(
                $submission
            );

        $submission->load([
            'conference.settings',
        ]);

        if (
            !$submission->conference?->settings?->review_enabled
        ) {
            return redirect()
                ->route(
                    'participant.submissions.show',
                    $submission
                )
                ->with(
                    'error',
                    'Review workflow is currently disabled.'
                );
        }

        if (
            $submission->status !== 'revision'
        ) {
            return redirect()
                ->route(
                    'participant.submissions.show',
                    $submission
                )
                ->with(
                    'error',
                    'This submission is not currently requesting a revision.'
                );
        }

        return view(
            'participant.submissions.revision',
            compact(
                'submission',
                'participant'
            )
        );
    }

    public function uploadRevision(
        RevisionRequest $request,
        Submission $submission
    ) {
        $this->getOwnedSubmissionParticipant(
            $submission
        );

        $submission->load([
            'conference.settings',
        ]);

        if (
            !$submission->conference?->settings?->review_enabled
        ) {
            return redirect()
                ->route(
                    'participant.submissions.show',
                    $submission
                )
                ->with(
                    'error',
                    'Review workflow is currently disabled.'
                );
        }

        if (
            $submission->status !== 'revision'
        ) {
            return redirect()
                ->route(
                    'participant.submissions.show',
                    $submission
                )
                ->with(
                    'error',
                    'This submission is not currently requesting a revision.'
                );
        }

        $oldFile =
            $submission->revised_file;

        $newFile = $request
            ->file('revised_file')
            ->store(
                'submissions/revisions',
                'public'
            );

        DB::transaction(function () use (
            $submission,
            $oldFile,
            $newFile
        ) {
            $currentRound = Review::where(
                'submission_id',
                $submission->id
            )
                ->max('review_round');

            $nextRound = $currentRound
                ? $currentRound + 1
                : 1;

            $submission->update([
                'revised_file' =>
                $newFile,

                'status' =>
                'under_review',
            ]);

            $oldReviews = Review::where(
                'submission_id',
                $submission->id
            )
                ->where(
                    'review_round',
                    $currentRound
                )
                ->get();

            foreach (
                $oldReviews as $oldReview
            ) {
                Review::create([
                    'submission_id' =>
                    $submission->id,

                    'reviewer_id' =>
                    $oldReview->reviewer_id,

                    'review_round' =>
                    $nextRound,

                    'score' =>
                    null,

                    'comment' =>
                    null,

                    'recommendation' =>
                    null,

                    'reviewed_at' =>
                    null,
                ]);
            }

            if ($oldFile) {
                Storage::disk('public')
                    ->delete(
                        $oldFile
                    );
            }
        });

        return redirect()
            ->route(
                'participant.submissions.show',
                $submission
            )
            ->with(
                'success',
                'Revised paper uploaded successfully and sent back for review.'
            );
    }

    public function cameraReady(
        Submission $submission
    ) {
        $participant = $this->getOwnedSubmissionParticipant($submission);

        $submission->load([
            'conference.settings',
        ]);

        if (
            !$submission->conference?->settings?->submission_enabled
            || $submission->conference?->settings?->maintenance_mode
        ) {
            return redirect()
                ->route(
                    'participant.submissions.show',
                    $submission
                )
                ->with(
                    'error',
                    'Submission workflow is currently unavailable.'
                );
        }

        if (
            $submission->status !== 'accepted'
        ) {
            return redirect()
                ->route(
                    'participant.submissions.show',
                    $submission
                )
                ->with(
                    'error',
                    'Camera-ready submission is only available for accepted papers.'
                );
        }

        return view(
            'participant.submissions.camera-ready',
            compact(
                'submission',
                'participant'
            )
        );
    }

    public function uploadCameraReady(
        CameraReadyRequest $request,
        Submission $submission
    ) {
        $this->getOwnedSubmissionParticipant(
            $submission
        );

        $submission->load([
            'conference.settings',
        ]);

        if (
            !$submission->conference?->settings?->submission_enabled
            || $submission->conference?->settings?->maintenance_mode
        ) {
            return redirect()
                ->route(
                    'participant.submissions.show',
                    $submission
                )
                ->with(
                    'error',
                    'Submission workflow is currently unavailable.'
                );
        }

        if (
            $submission->status !== 'accepted'
        ) {
            return redirect()
                ->route(
                    'participant.submissions.show',
                    $submission
                )
                ->with(
                    'error',
                    'Camera-ready submission is only available for accepted papers.'
                );
        }

        $oldFile =
            $submission->camera_ready_file;

        $newFile = $request
            ->file('camera_ready_file')
            ->store(
                'submissions/camera-ready',
                'public'
            );

        DB::transaction(function () use (
            $submission,
            $oldFile,
            $newFile
        ) {
            $submission->update([
                'camera_ready_file' =>
                $newFile,

                'status' =>
                'camera_ready',
            ]);

            if ($oldFile) {
                Storage::disk('public')
                    ->delete(
                        $oldFile
                    );
            }
        });

        return redirect()
            ->route(
                'participant.submissions.show',
                $submission
            )
            ->with(
                'success',
                'Camera-ready paper uploaded successfully.'
            );
    }

    private function getOwnedSubmissionParticipant(
        Submission $submission
    ): Participant {
        return Participant::where(
            'user_id',
            Auth::id()
        )
            ->where(
                'id',
                $submission->participant_id
            )
            ->firstOrFail();
    }

    private function generateSubmissionCode(
        $conference
    ): string {
        $prefix =
            strtoupper(
                $conference->short_name
            );

        $year =
            $conference->year;

        do {
            $code =
                $prefix .
                '-' .
                $year .
                '-' .
                strtoupper(
                    Str::random(8)
                );
        } while (
            Submission::where(
                'submission_code',
                $code
            )->exists()
        );

        return $code;
    }
}
