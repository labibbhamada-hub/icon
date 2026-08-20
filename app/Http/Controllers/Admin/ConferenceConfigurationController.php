<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConferenceConfigurationController extends Controller
{
    public function edit(Conference $conference)
    {
        $configuration = $conference->configuration;

        return view('admin.conference-configurations.edit', compact('conference', 'configuration'));
    }

    public function update(
        Request $request,
        Conference $conference
    ) {
        $validated = $request->validate([
            'bank_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'account_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'account_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'regular_fee' => [
                'required',
                'numeric',
                'min:0',
            ],

            'student_fee' => [
                'required',
                'numeric',
                'min:0',
            ],

            'chair_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'chair_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'signature_file' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $configuration = $conference->configuration;

        if (!$configuration) {
            $configuration = $conference->configuration()->create();
        }

        if ($request->hasFile('logo')) {
            if ($configuration->logo) {
                Storage::disk('public')->delete(
                    $configuration->logo
                );
            }

            $validated['logo'] = $request
                ->file('logo')
                ->store(
                    'conference-configurations/logos',
                    'public'
                );
        }

        if ($request->hasFile('signature_file')) {
            if ($configuration->signature_file) {
                Storage::disk('public')->delete(
                    $configuration->signature_file
                );
            }

            $validated['signature_file'] = $request
                ->file('signature_file')
                ->store(
                    'conference-configurations/signatures',
                    'public'
                );
        }

        $configuration->update($validated);

        return redirect()
            ->route(
                'admin.conferences.show',
                $conference
            )
            ->with(
                'success',
                'Conference configuration updated successfully.'
            );
    }
}
