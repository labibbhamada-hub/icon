<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conference;
use App\Models\ConferenceAttendanceOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConferenceSettingController extends Controller
{
    public function edit(Conference $conference)
    {
        $settings = $conference->setting;

        if (!$settings) {
            $settings = $conference->setting()->create([
                'is_active' => false,
                'registration_enabled' => false,
                'submission_enabled' => false,
                'payment_enabled' => false,
                'review_enabled' => false,
                'certificate_enabled' => false,
                'published' => false,
                'maintenance_mode' => false,
            ]);
        }

        $attendanceOptions = $conference
            ->attendanceOptions
            ->pluck('type')
            ->values()
            ->all();

        return view(
            'admin.conference-settings.settings',
            compact(
                'conference',
                'settings',
                'attendanceOptions'
            )
        );
    }

    public function update(Request $request, Conference $conference)
    {
        $validated = $request->validate([
            'is_active' => [
                'nullable',
                'boolean',
            ],

            'registration_enabled' => [
                'nullable',
                'boolean',
            ],

            'submission_enabled' => [
                'nullable',
                'boolean',
            ],

            'payment_enabled' => [
                'nullable',
                'boolean',
            ],

            'review_enabled' => [
                'nullable',
                'boolean',
            ],

            'certificate_enabled' => [
                'nullable',
                'boolean',
            ],

            'published' => [
                'nullable',
                'boolean',
            ],

            'maintenance_mode' => [
                'nullable',
                'boolean',
            ],

            'attendance_types' => [
                'required',
                'array',
                'min:1',
            ],

            'attendance_types.*' => [
                'required',
                'string',
                'in:online,offline,hybrid',
            ],
        ]);
        
        DB::transaction(function () use (
            $validated,
            $conference
        ) {
            $settings = $conference->setting;

            if (!$settings) {
                $settings = $conference->setting()->create();
            }

            $settings->update([
                'is_active' =>
                (bool) ($validated['is_active'] ?? false),

                'registration_enabled' =>
                (bool) ($validated['registration_enabled'] ?? false),

                'submission_enabled' =>
                (bool) ($validated['submission_enabled'] ?? false),

                'payment_enabled' =>
                (bool) ($validated['payment_enabled'] ?? false),

                'review_enabled' =>
                (bool) ($validated['review_enabled'] ?? false),

                'certificate_enabled' =>
                (bool) ($validated['certificate_enabled'] ?? false),

                'published' =>
                (bool) ($validated['published'] ?? false),

                'maintenance_mode' =>
                (bool) ($validated['maintenance_mode'] ?? false),
            ]);

            $conference->attendanceOptions()->delete();

            foreach (
                $validated['attendance_types'] as $index => $type
            ) {
                $conference->attendanceOptions()->create([
                    'type' => $type,
                    'sort_order' => $index + 1,
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
                'Conference settings updated successfully.'
            );
    }
}
