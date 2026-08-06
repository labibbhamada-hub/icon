<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TopicRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'icon' => [
                'nullable',
                'string',
                'max:100',
            ],
            'color' => [
                'required',
                'string',
                'max:30',
            ],
            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
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
            'conference_id.required' => 'Conference is required.',
            'conference_id.exists' => 'Conference not found.',
        ];
    }

    public function attributes(): array
    {
        return [
            'conference_id' => 'conference',
            'name' => 'topic name',
            'description' => 'description',
            'icon' => 'icon',
            'color' => 'color',
            'sort_order' => 'sort order',
            'is_active' => 'status',
        ];
    }
}
