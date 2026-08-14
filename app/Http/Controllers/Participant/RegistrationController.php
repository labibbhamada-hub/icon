<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Participant\RegistrationRequest;
use App\Models\Conference;
use App\Models\Participant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function index()
    {
        $registrations = Participant::with('conference')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view(
            'participant.registration.index',
            compact('registrations')
        );
    }

    public function create()
    {
        $registeredConferenceIds = Participant::where(
            'user_id',
            Auth::id()
        )
            ->pluck('conference_id');

        $conferences = Conference::where(
            'status',
            'registration_open'
        )
            ->whereNotIn(
                'id',
                $registeredConferenceIds
            )
            ->orderByDesc('year')
            ->get();

        return view(
            'participant.registration.create',
            compact('conferences')
        );
    }

    public function store(RegistrationRequest $request)
    {
        $data = $request->validated();

        $conference = Conference::findOrFail(
            $data['conference_id']
        );

        $registrationNumber =
            $this->generateRegistrationNumber(
                $conference
            );

        Participant::create([
            'user_id' => Auth::id(),

            'conference_id' =>
            $conference->id,

            'registration_number' =>
            $registrationNumber,

            'full_name' =>
            Auth::user()->name,

            'email' =>
            Auth::user()->email,

            'phone' =>
            $data['phone'] ?? null,

            'institution' =>
            $data['institution'] ?? null,

            'department' =>
            $data['department'] ?? null,

            'country' =>
            $data['country'],

            'city' =>
            $data['city'] ?? null,

            'participant_type' =>
            $data['participant_type'],

            'attendance_type' =>
            $data['attendance_type'],

            'registration_status' =>
            'pending',

            'registered_at' =>
            now(),
        ]);

        return redirect()
            ->route(
                'participant.registration.index'
            )
            ->with(
                'success',
                'Conference registration submitted successfully.'
            );
    }

    private function generateRegistrationNumber(
        Conference $conference
    ): string {
        do {

            $code = sprintf(
                '%s-%s-%04d',
                strtoupper(
                    $conference->short_name
                ),
                $conference->year,
                random_int(1, 9999)
            );
        } while (
            Participant::where(
                'registration_number',
                $code
            )->exists()
        );

        return $code;
    }
}
