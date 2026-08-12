<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ParticipantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $participant = $this->route('participant');

        $participantId = $participant instanceof \App\Models\Participant
            ? $participant->id
            : null;

        return [
            'conference_id' => [
                'required',
                'exists:conferences,id',
            ],

            'registration_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('participants', 'registration_number')
                    ->ignore($participantId),
            ],

            'full_name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
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

            'participant_type' => [
                'required',
                Rule::in([
                    'regular',
                    'student',
                    'speaker',
                    'committee',
                ]),
            ],

            'attendance_type' => [
                'required',
                Rule::in([
                    'offline',
                    'online',
                    'hybrid',
                ]),
            ],

            'registration_status' => [
                'required',
                Rule::in([
                    'pending',
                    'confirmed',
                    'cancelled',
                ]),
            ],

            'notes' => [
                'nullable',
                'string',
            ],

            'registered_at' => [
                'nullable',
                'date',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'conference_id' => 'conference',
            'registration_number' => 'registration number',
            'full_name' => 'full name',
            'email' => 'email address',
            'phone' => 'phone number',
            'institution' => 'institution',
            'department' => 'department',
            'country' => 'country',
            'city' => 'city',
            'participant_type' => 'participant type',
            'attendance_type' => 'attendance type',
            'registration_status' => 'registration status',
            'notes' => 'notes',
            'registered_at' => 'registration date',
        ];
    }
}
