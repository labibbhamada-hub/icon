<?php

namespace App\Http\Requests\Participant;

use App\Models\Conference;
use App\Models\ConferenceRegistrationType;
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
        $conference = Conference::with([
            'attendanceOptions',
            'registrationTypes',
        ])
            ->whereHas('setting', function ($query) {
                $query
                    ->where(
                        'is_active',
                        true
                    )
                    ->where(
                        'published',
                        true
                    )
                    ->where(
                        'registration_enabled',
                        true
                    )
                    ->where(
                        'maintenance_mode',
                        false
                    );
            })
            ->find(
                $this->input('conference_id')
            );

        $attendanceTypes = $conference
            ? $conference->attendanceOptions
            ->pluck('type')
            ->values()
            ->all()
            : [];

        return [
            'conference_id' => [
                'required',
                Rule::exists(
                    'conferences',
                    'id'
                ),
            ],

            'registration_type_id' => [
                'required',
                Rule::exists(
                    'conference_registration_types',
                    'id'
                )
                    ->where(function ($query) use ($conference) {
                        if (!$conference) {
                            $query->whereRaw(
                                '1 = 0'
                            );

                            return;
                        }

                        $query
                            ->where(
                                'conference_id',
                                $conference->id
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
                Rule::in(
                    $attendanceTypes
                ),
            ],

            'presentation_type' => [
                'nullable',
                'string',
                Rule::in([
                    'oral',
                    'poster',
                ]),
                Rule::requiredIf(function () {
                    $registrationType = ConferenceRegistrationType::where(
                        'id',
                        $this->input('registration_type_id')
                    )
                        ->where(
                            'conference_id',
                            $this->input('conference_id')
                        )
                        ->where(
                            'is_active',
                            true
                        )
                        ->first();

                    return $registrationType?->category === 'presenter';
                }),
            ],

            'title_prefix' => [
                'nullable',
                'string',
                'max:50',
            ],

            'title_suffix' => [
                'nullable',
                'string',
                'max:100',
            ],

            'orcid' => [
                'nullable',
                'string',
                'max:19',
                'regex:/^\d{4}-\d{4}-\d{4}-\d{4}$/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'conference_id.required' =>
            'Conference is required.',

            'conference_id.exists' =>
            'The selected conference is not available.',

            'registration_type_id.required' =>
            'Please select how you would like to participate.',

            'registration_type_id.exists' =>
            'The selected registration type is not available for this conference.',

            'country.required' =>
            'Country is required.',

            'attendance_type.required' =>
            'Please select how you will attend the conference.',

            'attendance_type.in' =>
            'The selected attendance option is not available for this conference.',

            'presentation_type.required' =>
            'Please select Oral or Poster presentation.',
        ];
    }
}
