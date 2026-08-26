<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Participant\PresentationRequest;
use App\Models\Submission;
use App\Models\SubmissionAuthor;
use Illuminate\Support\Facades\Auth;

class PresentationController extends Controller
{
    public function edit(Submission $submission)
    {
        $participant = Auth::user()
            ->participants()
            ->where('id', $submission->participant_id)
            ->firstOrFail();
        abort_unless(
            $submission->status === 'accepted',
            403
        );
        $submission->load('authors');
        return view(
            'participant.submissions.presentation',
            compact(
                'submission',
                'participant'
            )
        );
    }
    public function update(
        PresentationRequest $request,
        Submission $submission
    ) {
        $participant = Auth::user()
            ->participants()
            ->where('id', $submission->participant_id)
            ->firstOrFail();

        abort_unless(
            $submission->status === 'accepted',
            403
        );

        $validated = $request->validated();

        $presenterAuthor = $submission->authors()
            ->where(
                'id',
                $validated['presenter_author_id']
            )
            ->firstOrFail();

        $presentationMode = match ($participant->attendance_type) {
            'offline' => 'offline',
            'online' => 'online',
            'hybrid' => $validated['presentation_mode'],
            default => null,
        };

        abort_unless(
            $presentationMode !== null,
            422
        );

        $submission->update([
            'presentation_type' =>
            $validated['presentation_type'],

            'presentation_mode' =>
            $presentationMode,

            'presenter_author_id' =>
            $presenterAuthor->id,
        ]);

        return redirect()
            ->route(
                'participant.submissions.show',
                $submission
            )
            ->with(
                'success',
                'Presentation details updated successfully.'
            );
    }
}
