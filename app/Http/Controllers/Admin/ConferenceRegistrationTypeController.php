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
            'presentationPrices',
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

        /*
        |--------------------------------------------------------------------------
        | Extract presenter pricing
        |--------------------------------------------------------------------------
        */

        $oralFee = $data['oral_fee'] ?? null;
        $posterFee = $data['poster_fee'] ?? null;

        unset(
            $data['oral_fee'],
            $data['poster_fee']
        );

        /*
        |--------------------------------------------------------------------------
        | Presenter does not use the legacy registration fee
        |--------------------------------------------------------------------------
        */

        if ($data['category'] === 'presenter') {
            $data['fee'] = 0;
        }

        $registrationType =
            ConferenceRegistrationType::create(
                $data
            );

        /*
        |--------------------------------------------------------------------------
        | Save presenter prices
        |--------------------------------------------------------------------------
        */

        if (
            $registrationType->category ===
            'presenter'
        ) {
            $this->syncPresentationPrices(
                $registrationType,
                $oralFee,
                $posterFee,
                $registrationType->currency
            );
        }

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
            'presentationPrices',
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
        $conferenceRegistrationType->load([
            'presentationPrices',
        ]);

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

        /*
        |--------------------------------------------------------------------------
        | Extract presenter pricing
        |--------------------------------------------------------------------------
        */

        $oralFee = $data['oral_fee'] ?? null;
        $posterFee = $data['poster_fee'] ?? null;

        unset(
            $data['oral_fee'],
            $data['poster_fee']
        );

        /*
        |--------------------------------------------------------------------------
        | Presenter does not use legacy fee
        |--------------------------------------------------------------------------
        */

        if ($data['category'] === 'presenter') {
            $data['fee'] = 0;
        }

        $conferenceRegistrationType->update(
            $data
        );

        /*
        |--------------------------------------------------------------------------
        | Sync presenter prices
        |--------------------------------------------------------------------------
        */

        if (
            $conferenceRegistrationType->category ===
            'presenter'
        ) {
            $this->syncPresentationPrices(
                $conferenceRegistrationType,
                $oralFee,
                $posterFee,
                $conferenceRegistrationType->currency
            );
        }

        return redirect()
            ->route(
                'admin.registration-types.index'
            )
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

    private function syncPresentationPrices(
        ConferenceRegistrationType $registrationType,
        $oralFee,
        $posterFee,
        string $currency
    ): void {
        $registrationType
            ->presentationPrices()
            ->updateOrCreate(
                [
                    'presentation_type' =>
                    'oral',
                ],
                [
                    'fee' =>
                    $oralFee,

                    'currency' =>
                    strtoupper(
                        $currency
                    ),

                    'is_active' =>
                    true,

                    'sort_order' =>
                    1,
                ]
            );

        $registrationType
            ->presentationPrices()
            ->updateOrCreate(
                [
                    'presentation_type' =>
                    'poster',
                ],
                [
                    'fee' =>
                    $posterFee,

                    'currency' =>
                    strtoupper(
                        $currency
                    ),

                    'is_active' =>
                    true,

                    'sort_order' =>
                    2,
                ]
            );
    }
}
