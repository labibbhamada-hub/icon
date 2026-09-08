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
            ->firstOrFail();

        abort_unless(
            $submission->submission_stage === 'full_paper'
                && $submission->status === 'accepted',
            403
        );

        $submission->load('authors');

        /*
        |--------------------------------------------------------------------------
        | Get presentation type already used by this registration
        |--------------------------------------------------------------------------
        |
        | The first accepted submission that already has a presentation type
        | determines the presentation type for the registration.
        |
        */

        $registrationPresentationType =
            Submission::where(
                'participant_id',
                $participant->id
            )
            ->where(
                'submission_stage',
                'full_paper'
            )
            ->where(
                'status',
                'accepted'
            )
            ->whereNotNull(
                'presentation_type'
            )
            ->orderBy('id')
            ->value('presentation_type');

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
            ->firstOrFail();

        abort_unless(
            $submission->submission_stage === 'full_paper'
                && $submission->status === 'accepted',
            403
        );

        $validated =
            $request->validated();

        $presenterAuthor =
            $submission->authors()
            ->where(
                'id',
                $validated['presenter_author_id']
            )
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Find existing presentation type in this registration
        |--------------------------------------------------------------------------
        */

        $existingPresentationType =
            Submission::where(
                'participant_id',
                $participant->id
            )
            ->where(
                'submission_stage',
                'full_paper'
            )
            ->where(
                'status',
                'accepted'
            )
            ->whereNotNull(
                'presentation_type'
            )
            ->where(
                'id',
                '!=',
                $submission->id
            )
            ->orderBy('id')
            ->value('presentation_type');

        /*
        |--------------------------------------------------------------------------
        | Check payment lock
        |--------------------------------------------------------------------------
        */

        $hasVerifiedPayment =
            $participant->payments()
            ->where(
                'status',
                'verified'
            )
            ->exists();

        /*
        |--------------------------------------------------------------------------
        | Determine presentation type
        |--------------------------------------------------------------------------
        */

        if (
            $hasVerifiedPayment
            && $submission->presentation_type
        ) {
            /*
            |--------------------------------------------------------------------------
            | Existing paper already has a type and payment is verified.
            | Keep the existing value.
            |--------------------------------------------------------------------------
            */

            $presentationType =
                $submission->presentation_type;
        } elseif (
            $existingPresentationType
        ) {
            /*
            |--------------------------------------------------------------------------
            | Another paper has already established the registration type.
            |--------------------------------------------------------------------------
            */

            if (
                $validated['presentation_type']
                !==
                $existingPresentationType
            ) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'All papers in the same Author / Presenter registration must use the same presentation type.'
                    );
            }

            $presentationType =
                $existingPresentationType;
        } else {
            /*
            |--------------------------------------------------------------------------
            | First presentation type selection
            |--------------------------------------------------------------------------
            */

            $presentationType =
                $validated['presentation_type'];
        }

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
