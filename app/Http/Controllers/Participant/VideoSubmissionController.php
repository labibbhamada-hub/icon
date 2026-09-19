<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Participant\VideoSubmissionRequest;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VideoSubmissionController extends Controller
{
    public function edit(Submission $submission)
    {
        $participant = Auth::user()
            ->participants()
            ->where(
                'id',
                $submission->participant_id
            )
            ->where(
                'registration_status',
                'confirmed'
            )
            ->with('registrationType')
            ->firstOrFail();

        abort_unless(
            $participant->registrationType?->category === 'presenter',
            403
        );

        abort_unless(
            $submission->submission_stage === 'full_paper'
                && $submission->status === 'accepted',
            403
        );

        $submission->load([
            'authors',
        ]);

        return view(
            'participant.submissions.video',
            compact(
                'submission',
                'participant'
            )
        );
    }

    public function update(
        VideoSubmissionRequest $request,
        Submission $submission
    ) {
        $participant = Auth::user()
            ->participants()
            ->where(
                'id',
                $submission->participant_id
            )
            ->where(
                'registration_status',
                'confirmed'
            )
            ->with('registrationType')
            ->firstOrFail();

        abort_unless(
            $participant->registrationType?->category === 'presenter',
            403
        );

        abort_unless(
            $submission->submission_stage === 'full_paper'
                && $submission->status === 'accepted',
            403
        );

        $validated = $request->validated();

        $presenterAuthor = $submission
            ->authors()
            ->where(
                'id',
                $validated['presenter_author_id']
            )
            ->firstOrFail();

        DB::transaction(
            function () use (
                $submission,
                $validated,
                $presenterAuthor
            ) {
                $submission->update([
                    'presenter_author_id' =>
                    $presenterAuthor->id,

                    'video_url' =>
                    $validated['video_url'],

                    'video_submitted_at' =>
                    now(),
                ]);
            }
        );

        return redirect()
            ->route(
                'participant.submissions.show',
                $submission
            )
            ->with(
                'success',
                'Video submission updated successfully.'
            );
    }
}
