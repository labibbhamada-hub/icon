<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ConferenceConfigurationController extends Controller
{
    public function edit(Conference $conference)
    {
        $conference->load('onlineMeeting');

        $configuration = $conference->configuration;

        return view(
            'admin.conference-configurations.edit',
            compact(
                'conference',
                'configuration'
            )
        );
    }

    public function update(
        Request $request,
        Conference $conference
    ) {
        $validated = $request->validate([
            /*
            |--------------------------------------------------------------------------
            | Certificate / Branding
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | Online Meeting
            |--------------------------------------------------------------------------
            */

            'meeting_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'meeting_url' => [
                'nullable',
                'url',
                'max:2000',
            ],

            'meeting_id' => [
                'nullable',
                'string',
                'max:100',
            ],

            'passcode' => [
                'nullable',
                'string',
                'max:100',
            ],

            'meeting_instructions' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'meeting_is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        DB::transaction(function () use (
            $request,
            $conference,
            $validated
        ) {
            /*
            |--------------------------------------------------------------------------
            | Conference configuration
            |--------------------------------------------------------------------------
            */

            $configuration =
                $conference->configuration;

            if (!$configuration) {
                $configuration =
                    $conference
                    ->configuration()
                    ->create();
            }

            /*
            |--------------------------------------------------------------------------
            | Logo
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('logo')) {
                if ($configuration->logo) {
                    Storage::disk('public')->delete(
                        $configuration->logo
                    );
                }

                $validated['logo'] =
                    $request
                    ->file('logo')
                    ->store(
                        'conference-configurations/logos',
                        'public'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Signature
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('signature_file')) {
                if ($configuration->signature_file) {
                    Storage::disk('public')->delete(
                        $configuration->signature_file
                    );
                }

                $validated['signature_file'] =
                    $request
                    ->file('signature_file')
                    ->store(
                        'conference-configurations/signatures',
                        'public'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Remove meeting-only fields
            |--------------------------------------------------------------------------
            */

            unset(
                $validated['meeting_title'],
                $validated['meeting_url'],
                $validated['meeting_id'],
                $validated['passcode'],
                $validated['meeting_instructions'],
                $validated['meeting_is_active']
            );

            /*
            |--------------------------------------------------------------------------
            | Update configuration
            |--------------------------------------------------------------------------
            */

            $configuration->update(
                $validated
            );

            /*
            |--------------------------------------------------------------------------
            | Online Meeting
            |--------------------------------------------------------------------------
            */

            $meetingTitle =
                $request->input(
                    'meeting_title'
                );

            $meetingUrl =
                $request->input(
                    'meeting_url'
                );

            $meetingId =
                $request->input(
                    'meeting_id'
                );

            $passcode =
                $request->input(
                    'passcode'
                );

            $meetingInstructions =
                $request->input(
                    'meeting_instructions'
                );

            $meetingActive =
                $request->boolean(
                    'meeting_is_active'
                );

            /*
            |--------------------------------------------------------------------------
            | Create / update meeting only when meeting data exists
            |--------------------------------------------------------------------------
            */

            if (
                $meetingTitle
                || $meetingUrl
                || $meetingId
                || $passcode
                || $meetingInstructions
            ) {
                $conference
                    ->onlineMeeting()
                    ->updateOrCreate(
                        [
                            'conference_id' =>
                            $conference->id,
                        ],
                        [
                            'title' =>
                            $meetingTitle
                                ?: 'Online Conference Meeting',

                            'meeting_url' =>
                            $meetingUrl,

                            'meeting_id' =>
                            $meetingId,

                            'passcode' =>
                            $passcode,

                            'instructions' =>
                            $meetingInstructions,

                            'is_active' =>
                            $meetingActive,
                        ]
                    );
            } elseif (
                $conference->onlineMeeting
            ) {
                /*
                |--------------------------------------------------------------------------
                | If all meeting fields are cleared, deactivate meeting
                |--------------------------------------------------------------------------
                */

                $conference
                    ->onlineMeeting()
                    ->update([
                        'is_active' => false,
                    ]);
            }
        });

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
