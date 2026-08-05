<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConferenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'short_name' => [
                'required',
                'string',
                'max:50',
            ],
            'year' => [
                'required',
                'digits:4',
            ],
            'theme' => [
                'nullable',
                'string',
            ],
            'venue' => [
                'nullable',
                'string',
                'max:255',
            ],
            'city' => [
                'nullable',
                'string',
                'max:100',
            ],
            'country' => [
                'required',
                'string',
                'max:100',
            ],
            'start_date' => [
                'required',
                'date',
            ],
            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],
            'abstract_deadline' => [
                'nullable',
                'date',
            ],
            'fullpaper_deadline' => [
                'nullable',
                'date',
            ],
            'registration_deadline' => [
                'nullable',
                'date',
            ],
            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'banner' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],
            'status' => [
                'required',
                Rule::in([
                    'draft',
                    'registration_open',
                    'submission_open',
                    'review',
                    'camera_ready',
                    'closed',
                    'archived',
                ]),
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'conference name',
            'short_name' => 'short name',
            'year' => 'year',
            'theme' => 'theme',
            'venue' => 'venue',
            'city' => 'city',
            'country' => 'country',
            'start_date' => 'start date',
            'end_date' => 'end date',
            'abstract_deadline' => 'abstract deadline',
            'fullpaper_deadline' => 'full paper deadline',
            'registration_deadline' => 'registration deadline',
            'logo' => 'logo',
            'banner' => 'banner',
            'status' => 'status',
        ];
    }
}
