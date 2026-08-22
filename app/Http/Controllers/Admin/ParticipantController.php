<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ParticipantRequest;
use App\Models\Conference;
use App\Models\Participant;
use App\Exports\ParticipantsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function index()
    {
        $participants = Participant::with('conference')->latest('registered_at')->latest('id')->paginate(15);

        return view('admin.participants.index', compact('participants'));
    }

    public function create()
    {
        $conferences = Conference::orderByDesc('year')->get();

        return view('admin.participants.create', compact('conferences'));
    }

    public function store(ParticipantRequest $request)
    {
        $data = $request->validated();

        if (empty($data['registered_at'])) {
            $data['registered_at'] = now();
        }

        Participant::create($data);

        return redirect()->route('admin.participants.index')->with('success', 'Participant created successfully.');
    }

    public function show(Participant $participant)
    {
        $participant->load('conference');

        return view('admin.participants.show', compact('participant'));
    }

    public function edit(Participant $participant)
    {
        $conferences = Conference::orderByDesc('year')->get();

        return view('admin.participants.edit', compact('participant', 'conferences'));
    }

    public function update(ParticipantRequest $request, Participant $participant)
    {
        $participant->update($request->validated());

        return redirect()->route('admin.participants.index')->with('success', 'Participant updated successfully.');
    }

    public function destroy(Participant $participant)
    {
        $participant->delete();

        return redirect()->route('admin.participants.index')->with('success', 'Participant deleted successfully.');
    }

    public function export(Request $request)
    {
        $conferenceId = $request->integer('conference_id');

        $suffix = $conferenceId
            ? '-conference-' . $conferenceId
            : '-all';

        return Excel::download(
            new ParticipantsExport($conferenceId),
            'participants' .
                $suffix .
                '-' .
                now()->format('Y-m-d') .
                '.xlsx'
        );
    }
}
