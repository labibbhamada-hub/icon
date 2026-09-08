<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConferenceWhatsappGroupRequest extends FormRequest
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

            'group_url' => [
                'required',
                'url',
                'max:2048',
            ],

            'description' => [
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
            'WhatsApp group title is required.',

            'group_url.required' =>
            'WhatsApp group URL is required.',

            'group_url.url' =>
            'WhatsApp group URL must be a valid URL.',

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
            'WhatsApp group title',

            'group_url' =>
            'WhatsApp group URL',

            'description' =>
            'description',

            'is_active' =>
            'status',
        ];
    }
}
