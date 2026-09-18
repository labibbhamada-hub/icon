<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Participant\PresentationRequest;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PresentationController extends Controller
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

        $submission->load('authors');

        /*
        |--------------------------------------------------------------------------
        | Presentation type comes from registration
        |--------------------------------------------------------------------------
        |
        | Presentation type is selected during conference registration and
        | stored on the participant record.
        |
        */

        $registrationPresentationType =
            $participant->presentation_type;

        /*
        |--------------------------------------------------------------------------
        | Presentation type is locked after any verified payment
        |--------------------------------------------------------------------------
        */

        $presentationTypeLocked =
            $participant->payments()
            ->where(
                'status',
                'verified'
            )
            ->exists();

        return view(
            'participant.submissions.presentation',
            compact(
                'submission',
                'participant',
                'registrationPresentationType',
                'presentationTypeLocked'
            )
        );
    }

    public function update(
        PresentationRequest $request,
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

        $presenterAuthor =
            $submission->authors()
            ->where(
                'id',
                $validated['presenter_author_id']
            )
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Presentation type comes from registration
        |--------------------------------------------------------------------------
        */

        $presentationType =
            $participant->presentation_type;

        abort_unless(
            $presentationType !== null,
            422
        );

        /*
        |--------------------------------------------------------------------------
        | Presentation mode follows attendance type
        |--------------------------------------------------------------------------
        */

        $presentationMode = match ($participant->attendance_type) {
            'offline' => 'offline',

            'online' => 'online',

            'hybrid' =>
            $validated['presentation_mode'],

            default => null,
        };

        abort_unless(
            $presentationMode !== null,
            422
        );

        DB::transaction(
            function () use (
                $submission,
                $presentationType,
                $presentationMode,
                $presenterAuthor
            ) {
                $submission->update([
                    'presentation_type' =>
                    $presentationType,

                    'presentation_mode' =>
                    $presentationMode,

                    'presenter_author_id' =>
                    $presenterAuthor->id,

                    'presentation_completed' =>
                    true,
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
                'Presentation details updated successfully.'
            );
    }
}
