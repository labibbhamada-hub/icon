<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\ConferenceAttendance;
use App\Models\ImportantDate;
use App\Models\Participant;
use App\Services\CertificateGenerationService;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function checkIn(
        Participant $participant,
        CertificateGenerationService $certificateGenerationService
    ) {
        /*
        |--------------------------------------------------------------------------
        | Ownership
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $participant->user_id === auth()->id(),
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Registration
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $participant->registration_status === 'confirmed',
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Main Conference Event
        |--------------------------------------------------------------------------
        */

        $conferenceDate = ImportantDate::where(
            'conference_id',
            $participant->conference_id
        )
            ->where(
                'type',
                'conference'
            )
            ->where(
                'is_active',
                true
            )
            ->orderBy('date')
            ->orderBy('sort_order')
            ->first();

        if (!$conferenceDate) {
            return back()->with(
                'error',
                'Conference attendance is not available because the main conference date has not been configured.'
            );
        }

        $eventStart = $conferenceDate->date
            ->copy()
            ->startOfDay();

        $eventEnd = (
            $conferenceDate->end_date
            ?? $conferenceDate->date
        )
            ->copy()
            ->startOfDay();

        $today = now()->startOfDay();

        if ($today->lt($eventStart)) {
            return back()->with(
                'error',
                'Conference attendance will open on ' .
                    $eventStart->format('d F Y') .
                    '.'
            );
        }

        if ($today->gt($eventEnd)) {
            return back()->with(
                'error',
                'Conference attendance is already closed.'
            );
        }

        $participant->loadMissing(
            'registrationType'
        );

        $isPresenter =
            $participant->registrationType?->category === 'presenter';

        /*
        |--------------------------------------------------------------------------
        | Check In
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use ($participant) {
                $attendance = ConferenceAttendance::firstOrCreate(
                    [
                        'conference_id' =>
                        $participant->conference_id,

                        'participant_id' =>
                        $participant->id,
                    ],
                    [
                        'attendance_status' =>
                        'not_checked_in',
                    ]
                );

                if (
                    $attendance->attendance_status ===
                    'not_checked_in'
                ) {
                    $attendance->update([
                        'attendance_status' =>
                        'checked_in',

                        'checked_in_at' =>
                        now(),
                    ]);
                }
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Generate Attendance Certificate
        |--------------------------------------------------------------------------
        */

        try {
            $certificate = $isPresenter
                ? $certificateGenerationService
                ->createForPresenterAttendance(
                    $participant->fresh()
                )
                : $certificateGenerationService
                ->createForParticipant(
                    $participant->fresh()
                );
        } catch (\Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Attendance was recorded, but the certificate could not be generated yet. Please try again.'
            );
        }

        if ($isPresenter) {
            if ($certificate) {
                return back()->with(
                    'success',
                    'Conference check-in recorded successfully. Your presenter certificate is ready to download.'
                );
            }

            return back()->with(
                'success',
                'Conference check-in recorded successfully. Your presenter certificate will be available once an eligible full paper with a submitted presentation video is available.'
            );
        }

        return back()->with(
            'success',
            'Conference check-in recorded successfully. Your certificate is ready to download.'
        );
    }
}
