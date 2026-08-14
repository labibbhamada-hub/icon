<?php

namespace App\Http\Requests\Participant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'conference_id' => [
                'required',
                Rule::exists('conferences', 'id')
                    ->where(function ($query) {
                        $query->where(
                            'status',
                            'registration_open'
                        );
                    })
                    ->whereNotIn(
                        'id',
                        auth()->user()
                            ->participants()
                            ->pluck('conference_id')
                    ),
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
                Rule::in([
                    'offline',
                    'online',
                    'hybrid',
                ]),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'conference_id.required' => 'Please select a conference.',
            'conference_id.exists' => 'This conference is not currently open for registration or you have already registered for it.',
        ];
    }
}
