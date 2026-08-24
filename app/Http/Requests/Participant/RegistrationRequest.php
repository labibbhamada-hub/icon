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
                Rule::exists('conferences', 'id'),
            ],
            'registration_type_id' => [
                'required',
                Rule::exists('conference_registration_types', 'id')
                    ->where(function ($query) {
                        $query
                            ->where(
                                'conference_id',
                                $this->input('conference_id')
                            )
                            ->where(
                                'is_active',
                                true
                            );
                    }),
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
            'conference_id.required' => 'Conference is required.',
            'conference_id.exists' => 'The selected conference is not available.',
            'registration_type_id.required' => 'Please select how you will participate.',
            'registration_type_id.exists' => 'The selected registration type is not available for this conference.',
            'country.required' => 'Country is required.',
            'attendance_type.required' => 'Please select your attendance type.',
        ];
    }
}
