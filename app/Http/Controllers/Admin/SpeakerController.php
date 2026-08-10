<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SpeakerRequest;
use App\Models\Conference;
use App\Models\Speaker;
use Illuminate\Support\Facades\Storage;

class SpeakerController extends Controller
{
    public function index()
    {
        $speakers = Speaker::with('conference')
            ->orderBy('sort_order')
            ->latest()
            ->paginate(10);

        return view('admin.speakers.index', compact('speakers'));
    }

    public function create()
    {
        $conferences = Conference::orderByDesc('year')
            ->get();

        return view(
            'admin.speakers.create',
            compact('conferences')
        );
    }

    public function store(SpeakerRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request
                ->file('photo')
                ->store('speakers', 'public');
        }

        Speaker::create($data);

        return redirect()
            ->route('admin.speakers.index')
            ->with(
                'success',
                'Speaker created successfully.'
            );
    }

    public function show(Speaker $speaker)
    {
        $speaker->load('conference');

        return view(
            'admin.speakers.show',
            compact('speaker')
        );
    }

    public function edit(Speaker $speaker)
    {
        $conferences = Conference::orderByDesc('year')
            ->get();

        return view('admin.speakers.edit', compact('speaker', 'conferences'));
    }

    public function update(
        SpeakerRequest $request,
        Speaker $speaker
    ) {
        $data = $request->validated();

        if ($request->hasFile('photo')) {

            if ($speaker->photo) {
                Storage::disk('public')
                    ->delete($speaker->photo);
            }

            $data['photo'] = $request
                ->file('photo')
                ->store('speakers', 'public');
        }

        $speaker->update($data);

        return redirect()
            ->route('admin.speakers.index')
            ->with(
                'success',
                'Speaker updated successfully.'
            );
    }

    public function destroy(Speaker $speaker)
    {
        if ($speaker->photo) {
            Storage::disk('public')->delete($speaker->photo);
        }

        $speaker->delete();

        return redirect()
            ->route('admin.speakers.index')
            ->with(
                'success',
                'Speaker deleted successfully.'
            );
    }
}
