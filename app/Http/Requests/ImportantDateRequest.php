<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ImportantDateRequest extends FormRequest
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
            'conference_id' => ['required', 'exists:conferences,id'],

            'title' => ['required', 'string', 'max:255'],

            'type' => ['required', Rule::in(['abstract_submission', 'full_paper_submission', 'registration', 'conference', 'camera_ready', 'other'])],

            'description' => ['nullable', 'string'],

            'date' => ['required', 'date'],

            'end_date' => ['nullable', 'date', 'after_or_equal:date'],

            'sort_order' => ['nullable', 'integer', 'min:0'],

            'is_active' => ['required', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'conference_id' => 'conference',
            'title' => 'title',
            'type' => 'type',
            'description' => 'description',
            'date' => 'start date',
            'end_date' => 'end date',
            'sort_order' => 'sort order',
            'is_active' => 'status',
        ];
    }
}
