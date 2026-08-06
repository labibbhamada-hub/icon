<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConferenceRequest;
use App\Models\Conference;
use Illuminate\Support\Facades\Storage;

class ConferenceController extends Controller
{
    public function index()
    {
        $conferences = Conference::latest()->paginate(10);

        return view('admin.conferences.index', compact('conferences'));
    }

    public function create()
    {
        return view('admin.conferences.create');
    }

    public function store(ConferenceRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {

            $data['logo'] = $request
                ->file('logo')
                ->store('conference/logo', 'public');
        }

        if ($request->hasFile('banner')) {

            $data['banner'] = $request
                ->file('banner')
                ->store('conference/banner', 'public');
        }

        $conference = Conference::create($data);

        $conference->setting()->create([
            'is_active' => false,
        ]);

        return redirect()
            ->route('admin.conferences.index')
            ->with('success', 'Conference created successfully.');
    }

    public function show(Conference $conference)
    {
        return view('admin.conferences.show', compact('conference'));
    }

    public function edit(Conference $conference)
    {
        return view('admin.conferences.edit', compact('conference'));
    }

    public function update(ConferenceRequest $request, Conference $conference)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {

            if ($conference->logo) {
                Storage::disk('public')->delete($conference->logo);
            }

            $data['logo'] = $request
                ->file('logo')
                ->store('conference/logo', 'public');
        }

        if ($request->hasFile('banner')) {

            if ($conference->banner) {
                Storage::disk('public')->delete($conference->banner);
            }

            $data['banner'] = $request
                ->file('banner')
                ->store('conference/banner', 'public');
        }

        $conference->update($data);

        return redirect()
            ->route('admin.conferences.index')
            ->with(
                'success',
                'Conference updated successfully.'
            );
    }

    public function destroy(Conference $conference)
    {
        if ($conference->logo) {
            Storage::disk('public')->delete($conference->logo);
        }

        if ($conference->banner) {
            Storage::disk('public')->delete($conference->banner);
        }

        $conference->delete();

        return redirect()
            ->route('admin.conferences.index')
            ->with(
                'success',
                'Conference deleted successfully.'
            );
    }
}
