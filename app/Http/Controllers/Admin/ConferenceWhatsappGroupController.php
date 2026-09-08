<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConferenceWhatsappGroupRequest;
use App\Models\Conference;
use App\Models\ConferenceWhatsappGroup;
use Illuminate\Database\QueryException;

class ConferenceWhatsappGroupController extends Controller
{
    public function index()
    {
        $groups = ConferenceWhatsappGroup::with('conference')
            ->latest()
            ->paginate(10);

        return view(
            'admin.conference-whatsapp-groups.index',
            compact('groups')
        );
    }

    public function create()
    {
        $conferences = Conference::orderByDesc('year')->get();

        return view(
            'admin.conference-whatsapp-groups.create',
            compact('conferences')
        );
    }

    public function store(ConferenceWhatsappGroupRequest $request)
    {
        try {
            ConferenceWhatsappGroup::create(
                $request->validated()
            );
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return back()
                    ->withInput()
                    ->withErrors([
                        'conference_id' =>
                        'This conference already has a WhatsApp group.',
                    ]);
            }

            throw $e;
        }

        return redirect()
            ->route('admin.conference-whatsapp-groups.index')
            ->with(
                'success',
                'WhatsApp group created successfully.'
            );
    }

    public function show(ConferenceWhatsappGroup $conferenceWhatsappGroup)
    {
        $conferenceWhatsappGroup->load('conference');

        return view(
            'admin.conference-whatsapp-groups.show',
            compact('conferenceWhatsappGroup')
        );
    }

    public function edit(
        ConferenceWhatsappGroup $conferenceWhatsappGroup
    ) {
        $conferences = Conference::orderByDesc('year')->get();

        return view(
            'admin.conference-whatsapp-groups.edit',
            compact(
                'conferenceWhatsappGroup',
                'conferences'
            )
        );
    }

    public function update(
        ConferenceWhatsappGroupRequest $request,
        ConferenceWhatsappGroup $conferenceWhatsappGroup
    ) {
        try {
            $conferenceWhatsappGroup->update(
                $request->validated()
            );
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return back()
                    ->withInput()
                    ->withErrors([
                        'conference_id' =>
                        'This conference already has a WhatsApp group.',
                    ]);
            }

            throw $e;
        }

        return redirect()
            ->route('admin.conference-whatsapp-groups.index')
            ->with(
                'success',
                'WhatsApp group updated successfully.'
            );
    }

    public function destroy(
        ConferenceWhatsappGroup $conferenceWhatsappGroup
    ) {
        $conferenceWhatsappGroup->delete();

        return redirect()
            ->route('admin.conference-whatsapp-groups.index')
            ->with(
                'success',
                'WhatsApp group deleted successfully.'
            );
    }
}
