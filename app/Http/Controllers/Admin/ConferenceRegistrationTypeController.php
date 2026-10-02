<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConferenceRegistrationTypeRequest;
use App\Models\Conference;
use App\Models\ConferenceRegistrationType;

class ConferenceRegistrationTypeController extends Controller
{
    public function index()
    {
        $registrationTypes = ConferenceRegistrationType::with([
            'conference',
        ])
            ->orderBy('conference_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10);

        return view(
            'admin.registration-types.index',
            compact('registrationTypes')
        );
    }

    public function create()
    {
        $conferences = Conference::orderByDesc('year')->get();

        return view(
            'admin.registration-types.create',
            compact('conferences')
        );
    }

    public function store(
        ConferenceRegistrationTypeRequest $request
    ) {
        $data = $request->validated();

        $registrationType =
            ConferenceRegistrationType::create(
                $data
            );

        return redirect()
            ->route(
                'admin.registration-types.index'
            )
            ->with(
                'success',
                'Registration type created successfully.'
            );
    }

    public function show(
        ConferenceRegistrationType $conferenceRegistrationType
    ) {
        $conferenceRegistrationType->load([
            'conference',
        ]);

        return view(
            'admin.registration-types.show',
            compact(
                'conferenceRegistrationType'
            )
        );
    }

    public function edit(
        ConferenceRegistrationType $conferenceRegistrationType
    ) {
        $conferences =
            Conference::orderByDesc('year')
            ->get();

        return view(
            'admin.registration-types.edit',
            compact(
                'conferenceRegistrationType',
                'conferences'
            )
        );
    }

    public function update(
        ConferenceRegistrationTypeRequest $request,
        ConferenceRegistrationType $conferenceRegistrationType
    ) {
        $data = $request->validated();

        $hasParticipants = $conferenceRegistrationType
            ->participants()
            ->exists();

        if (
            $hasParticipants
            && (
                (int) $conferenceRegistrationType->conference_id !==
                (int) $data['conference_id']
                || $conferenceRegistrationType->category !==
                $data['category']
            )
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Registration type conference and category cannot be changed because it is already used by participants.'
                );
        }

        $conferenceRegistrationType->update($data);

        return redirect()
            ->route('admin.registration-types.index')
            ->with(
                'success',
                'Registration type updated successfully.'
            );
    }

    public function destroy(
        ConferenceRegistrationType $conferenceRegistrationType
    ) {
        if (
            $conferenceRegistrationType
            ->participants()
            ->exists()
        ) {
            return back()
                ->with(
                    'error',
                    'Registration type cannot be deleted because it is already used by participants.'
                );
        }

        $conferenceRegistrationType->delete();

        return redirect()
            ->route(
                'admin.registration-types.index'
            )
            ->with(
                'success',
                'Registration type deleted successfully.'
            );
    }
}
