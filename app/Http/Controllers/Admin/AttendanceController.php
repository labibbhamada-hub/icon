<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConferenceAttendance;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function manualCheckIn(
        Request $request,
        Participant $participant
    ) {
        $validated = $request->validate([
            'checked_in_at' => [
                'required',
                'date',
            ],
            'verification_notes' => [
                'required',
                'string',
                'max:2000',
            ],
        ]);

        abort_unless(
            $participant->registration_status === 'confirmed',
            403
        );

        $attendance = DB::transaction(function () use (
            $participant,
            $validated
        ) {
            $attendance = ConferenceAttendance::firstOrCreate(
                [
                    'conference_id' => $participant->conference_id,
                    'participant_id' => $participant->id,
                ],
                [
                    'attendance_status' => 'not_checked_in',
                ]
            );

            abort_if(
                $attendance->attendance_status === 'verified',
                403
            );

            $attendance->update([
                'attendance_status' => 'checked_in',
                'checked_in_at' => $validated['checked_in_at'],
                'verification_notes' => $validated['verification_notes'],
            ]);

            return $attendance->fresh();
        });

        return back()->with(
            'success',
            'Attendance check-in recorded manually.'
        );
    }

    public function verify(ConferenceAttendance $attendance)
    {
        abort_if(
            $attendance->attendance_status === 'not_checked_in',
            403
        );

        if ($attendance->attendance_status === 'verified') {
            return back()->with(
                'error',
                'This attendance has already been verified.'
            );
        }

        $attendance->update([
            'attendance_status' => 'verified',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        return back()->with(
            'success',
            'Attendance verified successfully.'
        );
    }
}
