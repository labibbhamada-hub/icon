<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\ConferenceAttendance;
use App\Models\Participant;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function checkIn(Participant $participant)
    {
        abort_unless(
            $participant->user_id === auth()->id(),
            403
        );

        abort_unless(
            $participant->registration_status === 'confirmed',
            403
        );

        $attendance = DB::transaction(function () use ($participant) {
            $attendance = ConferenceAttendance::firstOrCreate(
                [
                    'conference_id' => $participant->conference_id,
                    'participant_id' => $participant->id,
                ],
                [
                    'attendance_status' => 'not_checked_in',
                ]
            );

            if ($attendance->attendance_status === 'not_checked_in') {
                $attendance->update([
                    'attendance_status' => 'checked_in',
                    'checked_in_at' => now(),
                ]);
            }

            return $attendance->fresh();
        });

        return back()->with(
            'success',
            'Attendance check-in recorded successfully.'
        );
    }
}
