<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Participant\RegistrationRequest;
use App\Models\Conference;
use App\Models\Participant;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function index()
    {
        $registrations = Participant::with([
            'conference',
            'registrationType',
        ])
            ->where(
                'user_id',
                Auth::id()
            )
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

        $conference = Conference::with([
            'setting',
            'attendanceOptions',
            'registrationTypes' => function ($query) {
                $query
                    ->where(
                        'is_active',
                        true
                    )
                    ->orderBy('category')
                    ->orderBy('sort_order')
                    ->orderBy('name');
            },
        ])
            ->whereHas('setting', function ($query) {
                $query
                    ->where(
                        'is_active',
                        true
                    )
                    ->where(
                        'published',
                        true
                    )
                    ->where(
                        'registration_enabled',
                        true
                    )
                    ->where(
                        'maintenance_mode',
                        false
                    );
            })
            ->whereNotIn(
                'id',
                $registeredConferenceIds
            )
            ->orderByDesc('year')
            ->first();

        return view(
            'participant.registration.create',
            compact('conference')
        );
    }

    public function store(RegistrationRequest $request)
    {
        $data = $request->validated();

        $conference = Conference::with([
            'setting',
            'attendanceOptions',
        ])
            ->whereHas('setting', function ($query) {
                $query
                    ->where(
                        'is_active',
                        true
                    )
                    ->where(
                        'published',
                        true
                    )
                    ->where(
                        'registration_enabled',
                        true
                    )
                    ->where(
                        'maintenance_mode',
                        false
                    );
            })
            ->findOrFail(
                $data['conference_id']
            );

        $registrationType = $conference
            ->registrationTypes()
            ->where(
                'id',
                $data['registration_type_id']
            )
            ->where(
                'is_active',
                true
            )
            ->firstOrFail();

        $participantExists = Participant::where(
            'user_id',
            Auth::id()
        )
            ->where(
                'conference_id',
                $conference->id
            )
            ->exists();

        if ($participantExists) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'You are already registered for this conference.'
                );
        }

        $attendanceAvailable = $conference
            ->attendanceOptions
            ->contains(
                'type',
                $data['attendance_type']
            );

        if (!$attendanceAvailable) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'The selected attendance option is not available for this conference.'
                );
        }

        $registrationNumber =
            $this->generateRegistrationNumber(
                $conference
            );

        Participant::create([
            'user_id' =>
            Auth::id(),

            'conference_id' =>
            $conference->id,

            'registration_type_id' =>
            $registrationType->id,

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
            $this->resolveParticipantType(
                $registrationType
            ),

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

    private function resolveParticipantType(
        $registrationType
    ): string {
        if (
            $registrationType->category === 'presenter'
        ) {
            return 'presenter';
        }

        if (
            str_contains(
                strtolower(
                    $registrationType->code
                ),
                'student'
            )
        ) {
            return 'student';
        }

        return 'regular';
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
                random_int(
                    1,
                    9999
                )
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
