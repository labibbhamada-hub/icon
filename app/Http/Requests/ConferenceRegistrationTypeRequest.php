<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConferenceRegistrationTypeRequest extends FormRequest
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
        $registrationTypeId = $this->route('conference_registration_type')?->id;
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
            'code' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-z0-9_]+$/',
                Rule::unique('conference_registration_types', 'code')
                    ->where(function ($query) {
                        return $query->where(
                            'conference_id',
                            $this->conference_id
                        );
                    })
                    ->ignore($registrationTypeId),
            ],
            'category' => [
                'required',
                Rule::in([
                    'participant',
                    'presenter',
                ]),
            ],
            'fee' => [
                'required',
                'numeric',
                'min:0',
            ],
            'included_papers' => [
                'required',
                'integer',
                'min:0',
            ],
            'additional_paper_fee' => [
                'required',
                'numeric',
                'min:0',
            ],
            'currency' => [
                'required',
                'string',
                'max:10',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'benefits' => [
                'nullable',
                'string',
            ],
            'is_active' => [
                'required',
                'boolean',
            ],
            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ];
    }
    public function attributes(): array
    {
        return [
            'conference_id' => 'conference',
            'name' => 'name',
            'code' => 'code',
            'category' => 'category',
            'fee' => 'registration fee',
            'included_papers' => 'included papers',
            'additional_paper_fee' => 'additional paper fee',
            'currency' => 'currency',
            'description' => 'description',
            'benefits' => 'benefits',
            'is_active' => 'status',
            'sort_order' => 'sort order',
        ];
    }
}
