<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SpeakerRequest;
use App\Models\Conference;
use App\Models\Speaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

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

        $photoFile = null;

        try {
            if ($request->hasFile('photo')) {
                $photoFile = $request
                    ->file('photo')
                    ->store('speakers', 'public');

                $data['photo'] = $photoFile;
            }

            DB::transaction(function () use ($data) {
                Speaker::create($data);
            });
        } catch (Throwable $e) {
            if ($photoFile) {
                Storage::disk('public')->delete($photoFile);
            }

            throw $e;
        }

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

        return view(
            'admin.speakers.edit',
            compact(
                'speaker',
                'conferences'
            )
        );
    }

    public function update(
        SpeakerRequest $request,
        Speaker $speaker
    ) {
        $data = $request->validated();

        $oldPhotoFile = $speaker->photo;
        $newPhotoFile = null;

        try {
            if ($request->hasFile('photo')) {
                $newPhotoFile = $request
                    ->file('photo')
                    ->store('speakers', 'public');

                $data['photo'] = $newPhotoFile;
            }

            DB::transaction(function () use (
                $speaker,
                $data
            ) {
                $speaker->update($data);
            });
        } catch (Throwable $e) {
            if ($newPhotoFile) {
                Storage::disk('public')->delete($newPhotoFile);
            }

            throw $e;
        }

        if (
            $newPhotoFile
            && $oldPhotoFile
            && $oldPhotoFile !== $newPhotoFile
        ) {
            Storage::disk('public')->delete($oldPhotoFile);
        }

        return redirect()
            ->route('admin.speakers.index')
            ->with(
                'success',
                'Speaker updated successfully.'
            );
    }

    public function destroy(Speaker $speaker)
    {
        $photoFile = $speaker->photo;

        DB::transaction(function () use ($speaker) {
            $speaker->delete();
        });

        if ($photoFile) {
            Storage::disk('public')->delete($photoFile);
        }

        return redirect()
            ->route('admin.speakers.index')
            ->with(
                'success',
                'Speaker deleted successfully.'
            );
    }
}
