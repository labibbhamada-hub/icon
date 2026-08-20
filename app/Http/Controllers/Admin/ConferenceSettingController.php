<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conference;
use Illuminate\Http\Request;

class ConferenceSettingController extends Controller
{
    public function edit(Conference $conference)
    {
        $settings = $conference->setting;

        /*
        |--------------------------------------------------------------------------
        | Create default settings automatically if not exists
        |--------------------------------------------------------------------------
        */

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

        return view(
            'admin.conference-settings.settings',
            compact(
                'conference',
                'settings'
            )
        );
    }

    public function update(
        Request $request,
        Conference $conference
    ) {
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
        ]);

        $settings = $conference->setting;

        if (!$settings) {
            $settings = $conference->setting()->create();
        }

        /*
        |--------------------------------------------------------------------------
        | Checkbox yang tidak dicentang tidak dikirim oleh browser.
        | Karena itu kita set setiap field secara eksplisit.
        |--------------------------------------------------------------------------
        */

        $settings->update([
            'is_active' =>
            $request->boolean('is_active'),

            'registration_enabled' =>
            $request->boolean('registration_enabled'),

            'submission_enabled' =>
            $request->boolean('submission_enabled'),

            'payment_enabled' =>
            $request->boolean('payment_enabled'),

            'review_enabled' =>
            $request->boolean('review_enabled'),

            'certificate_enabled' =>
            $request->boolean('certificate_enabled'),

            'published' =>
            $request->boolean('published'),

            'maintenance_mode' =>
            $request->boolean('maintenance_mode'),
        ]);

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
