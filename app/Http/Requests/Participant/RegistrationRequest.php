<?php

namespace App\Http\Requests\Participant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Conference;

class RegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $conference = Conference::with([
            'attendanceOptions',
        ])
            ->whereHas('setting', function ($query) {
                $query
                    ->where('is_active', true)
                    ->where('published', true)
                    ->where('registration_enabled', true)
                    ->where('maintenance_mode', false);
            })
            ->find($this->input('conference_id'));

        $attendanceTypes = $conference
            ? $conference->attendanceOptions
            ->pluck('type')
            ->values()
            ->all()
            : [];

        return [
            'conference_id' => [
                'required',
                Rule::exists('conferences', 'id'),
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
                ]),
            ],

            'attendance_type' => [
                'required',
                Rule::in($attendanceTypes),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'conference_id.required' => 'Conference is required.',
            'conference_id.exists' => 'The selected conference is not available.',
            'registration_type_id.required' => 'Please select how you will participate.',
            'registration_type_id.exists' => 'The selected registration type is not available for this conference.',
            'country.required' => 'Country is required.',
            'attendance_type.required' => 'Please select how you will attend the conference.',
            'attendance_type.in' => 'The selected attendance option is not available for this conference.',
        ];
    }
}
