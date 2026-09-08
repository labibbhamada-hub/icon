<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConferenceOnlineMeetingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    public function rules(): array
    {
        return [
            'conference_id' => [
                'required',
                'exists:conferences,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'meeting_url' => [
                'required',
                'url',
                'max:2048',
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

            'instructions' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'conference_id.required' =>
            'Conference is required.',

            'conference_id.exists' =>
            'The selected conference does not exist.',

            'title.required' =>
            'Meeting title is required.',

            'meeting_url.required' =>
            'Meeting URL is required.',

            'meeting_url.url' =>
            'Meeting URL must be a valid URL.',

            'meeting_id.max' =>
            'Meeting ID may not exceed 100 characters.',

            'passcode.max' =>
            'Passcode may not exceed 100 characters.',

            'is_active.required' =>
            'Status is required.',
        ];
    }

    public function attributes(): array
    {
        return [
            'conference_id' =>
            'conference',

            'title' =>
            'meeting title',

            'meeting_url' =>
            'meeting URL',

            'meeting_id' =>
            'meeting ID',

            'passcode' =>
            'passcode',

            'instructions' =>
            'instructions',

            'is_active' =>
            'status',
        ];
    }
}
