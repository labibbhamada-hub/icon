<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConferenceRequest;
use App\Models\Conference;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

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

        $uploadedFiles = [];

        try {
            if ($request->hasFile('logo')) {
                $uploadedFiles['logo'] = $request
                    ->file('logo')
                    ->store('conference/logo', 'public');

                $data['logo'] = $uploadedFiles['logo'];
            }

            if ($request->hasFile('banner')) {
                $uploadedFiles['banner'] = $request
                    ->file('banner')
                    ->store('conference/banner', 'public');

                $data['banner'] = $uploadedFiles['banner'];
            }

            DB::transaction(function () use ($data) {
                $conference = Conference::create($data);

                $conference->setting()->create([
                    'is_active' => false,
                ]);
            });
        } catch (Throwable $e) {
            foreach ($uploadedFiles as $file) {
                Storage::disk('public')->delete($file);
            }

            throw $e;
        }

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

    public function update(
        ConferenceRequest $request,
        Conference $conference
    ) {
        $data = $request->validated();

        $oldFiles = [];
        $newFiles = [];

        try {
            if ($request->hasFile('logo')) {
                $newFiles['logo'] = $request
                    ->file('logo')
                    ->store('conference/logo', 'public');

                $data['logo'] = $newFiles['logo'];

                if ($conference->logo) {
                    $oldFiles['logo'] = $conference->logo;
                }
            }

            if ($request->hasFile('banner')) {
                $newFiles['banner'] = $request
                    ->file('banner')
                    ->store('conference/banner', 'public');

                $data['banner'] = $newFiles['banner'];

                if ($conference->banner) {
                    $oldFiles['banner'] = $conference->banner;
                }
            }

            DB::transaction(function () use (
                $conference,
                $data
            ) {
                $conference->update($data);
            });
        } catch (Throwable $e) {
            foreach ($newFiles as $file) {
                Storage::disk('public')->delete($file);
            }

            throw $e;
        }

        foreach ($oldFiles as $file) {
            Storage::disk('public')->delete($file);
        }

        return redirect()
            ->route('admin.conferences.index')
            ->with(
                'success',
                'Conference updated successfully.'
            );
    }

    public function destroy(Conference $conference)
    {
        $hasWorkflowData =
            $conference->participants()->exists()
            || $conference->submissions()->exists()
            || $conference->reviewers()->exists()
            || $conference->certificates()->exists()
            || $conference->attendances()->exists();

        if ($hasWorkflowData) {
            return back()
                ->with(
                    'error',
                    'Conference cannot be deleted because workflow records already exist.'
                );
        }

        $conference->load([
            'configuration',
            'paymentMethods',
            'partners',
            'speakers',
        ]);

        $filesToDelete = [];

        if ($conference->logo) {
            $filesToDelete[] = $conference->logo;
        }

        if ($conference->banner) {
            $filesToDelete[] = $conference->banner;
        }

        if ($conference->configuration) {
            if ($conference->configuration->logo) {
                $filesToDelete[] = $conference->configuration->logo;
            }

            if ($conference->configuration->signature_file) {
                $filesToDelete[] = $conference->configuration->signature_file;
            }
        }

        foreach ($conference->paymentMethods as $paymentMethod) {
            if ($paymentMethod->qr_code_file) {
                $filesToDelete[] = $paymentMethod->qr_code_file;
            }
        }

        foreach ($conference->partners as $partner) {
            if ($partner->logo) {
                $filesToDelete[] = $partner->logo;
            }
        }

        foreach ($conference->speakers as $speaker) {
            if ($speaker->photo) {
                $filesToDelete[] = $speaker->photo;
            }
        }

        DB::transaction(function () use ($conference) {
            $conference->delete();
        });

        foreach ($filesToDelete as $file) {
            Storage::disk('public')->delete($file);
        }

        return redirect()
            ->route('admin.conferences.index')
            ->with(
                'success',
                'Conference deleted successfully.'
            );
    }
}
