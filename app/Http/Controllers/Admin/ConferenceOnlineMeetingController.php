<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConferenceOnlineMeetingRequest;
use App\Models\Conference;
use App\Models\ConferenceOnlineMeeting;
use Illuminate\Database\QueryException;

class ConferenceOnlineMeetingController extends Controller
{
    public function index()
    {
        $meetings = ConferenceOnlineMeeting::with('conference')
            ->latest()
            ->paginate(10);

        return view(
            'admin.conference-online-meetings.index',
            compact('meetings')
        );
    }

    public function create()
    {
        $conferences = Conference::orderByDesc('year')->get();

        return view(
            'admin.conference-online-meetings.create',
            compact('conferences')
        );
    }

    public function store(ConferenceOnlineMeetingRequest $request)
    {
        try {
            ConferenceOnlineMeeting::create(
                $request->validated()
            );
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return back()
                    ->withInput()
                    ->withErrors([
                        'conference_id' =>
                        'This conference already has an online meeting.',
                    ]);
            }

            throw $e;
        }

        return redirect()
            ->route('admin.conference-online-meetings.index')
            ->with(
                'success',
                'Online meeting created successfully.'
            );
    }

    public function show(ConferenceOnlineMeeting $conferenceOnlineMeeting)
    {
        $conferenceOnlineMeeting->load('conference');

        return view(
            'admin.conference-online-meetings.show',
            compact('conferenceOnlineMeeting')
        );
    }

    public function edit(
        ConferenceOnlineMeeting $conferenceOnlineMeeting
    ) {
        $conferences = Conference::orderByDesc('year')->get();

        return view(
            'admin.conference-online-meetings.edit',
            compact(
                'conferenceOnlineMeeting',
                'conferences'
            )
        );
    }

    public function update(
        ConferenceOnlineMeetingRequest $request,
        ConferenceOnlineMeeting $conferenceOnlineMeeting
    ) {
        try {
            $conferenceOnlineMeeting->update(
                $request->validated()
            );
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return back()
                    ->withInput()
                    ->withErrors([
                        'conference_id' =>
                        'This conference already has an online meeting.',
                    ]);
            }

            throw $e;
        }

        return redirect()
            ->route('admin.conference-online-meetings.index')
            ->with(
                'success',
                'Online meeting updated successfully.'
            );
    }

    public function destroy(
        ConferenceOnlineMeeting $conferenceOnlineMeeting
    ) {
        $conferenceOnlineMeeting->delete();

        return redirect()
            ->route('admin.conference-online-meetings.index')
            ->with(
                'success',
                'Online meeting deleted successfully.'
            );
    }
}
