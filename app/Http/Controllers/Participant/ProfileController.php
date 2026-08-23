<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $participant = Participant::with('conference')
            ->where('user_id', Auth::id())
            ->latest()
            ->first();

        if (!$participant) {
            return redirect()
                ->route('participant.registration.create')
                ->with(
                    'info',
                    'Please register for a conference before completing your participant profile.'
                );
        }

        return view(
            'participant.profile.edit',
            compact('participant')
        );
    }

    public function update(Request $request)
    {
        $participant = Participant::where(
            'user_id',
            Auth::id()
        )
            ->latest()
            ->first();

        if (!$participant) {
            return redirect()
                ->route('participant.registration.create')
                ->with(
                    'info',
                    'Please register for a conference before updating your participant profile.'
                );
        }

        $validated = $request->validate([
            'full_name' => [
                'required',
                'string',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'institution' => [
                'nullable',
                'string',
                'max:255',
            ],

            'department' => [
                'nullable',
                'string',
                'max:255',
            ],

            'country' => [
                'required',
                'string',
                'max:100',
            ],

            'city' => [
                'nullable',
                'string',
                'max:100',
            ],
        ]);

        $participant->update($validated);

        return redirect()
            ->route('participant.profile.edit')
            ->with(
                'success',
                'Profile updated successfully.'
            );
    }
}
