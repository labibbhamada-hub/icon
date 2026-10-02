<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

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

        $configuration = $conference->configuration;

        $oldFiles = [];
        $newFiles = [];

        try {
            /*
            |--------------------------------------------------------------------------
            | Store new files first
            |--------------------------------------------------------------------------
            |
            | File lama belum dihapus. Jika proses database gagal,
            | file baru akan dibersihkan dan file lama tetap aman.
            |
            */

            if ($request->hasFile('logo')) {
                $newFiles['logo'] = $request
                    ->file('logo')
                    ->store(
                        'conference-configurations/logos',
                        'public'
                    );

                $validated['logo'] = $newFiles['logo'];

                if ($configuration?->logo) {
                    $oldFiles['logo'] = $configuration->logo;
                }
            }

            if ($request->hasFile('signature_file')) {
                $newFiles['signature_file'] = $request
                    ->file('signature_file')
                    ->store(
                        'conference-configurations/signatures',
                        'public'
                    );

                $validated['signature_file'] =
                    $newFiles['signature_file'];

                if ($configuration?->signature_file) {
                    $oldFiles['signature_file'] =
                        $configuration->signature_file;
                }
            }

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
                | Remove meeting-only fields
                |--------------------------------------------------------------------------
                */

                $configurationData = $validated;

                unset(
                    $configurationData['meeting_title'],
                    $configurationData['meeting_url'],
                    $configurationData['meeting_id'],
                    $configurationData['passcode'],
                    $configurationData['meeting_instructions'],
                    $configurationData['meeting_is_active']
                );

                /*
                |--------------------------------------------------------------------------
                | Update configuration
                |--------------------------------------------------------------------------
                */

                $configuration->update(
                    $configurationData
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
        } catch (Throwable $e) {
            /*
            |--------------------------------------------------------------------------
            | Database failed
            |--------------------------------------------------------------------------
            |
            | Hapus hanya file baru. File lama tidak disentuh.
            |
            */

            foreach ($newFiles as $file) {
                Storage::disk('public')->delete($file);
            }

            throw $e;
        }

        /*
        |--------------------------------------------------------------------------
        | Database succeeded
        |--------------------------------------------------------------------------
        |
        | File lama baru boleh dihapus setelah transaksi berhasil.
        |
        */

        foreach ($oldFiles as $file) {
            if (
                !in_array(
                    $file,
                    $newFiles,
                    true
                )
            ) {
                Storage::disk('public')->delete($file);
            }
        }

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
