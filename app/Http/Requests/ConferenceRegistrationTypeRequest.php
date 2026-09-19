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
        $registrationTypeId =
            $this->route('conference_registration_type')?->id;

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

                Rule::unique(
                    'conference_registration_types',
                    'code'
                )
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

            /*
            |--------------------------------------------------------------------------
            | Registration fee
            |--------------------------------------------------------------------------
            |
            | The registration fee is the actual fee for all registration types,
            | including presenter registration types.
            |
            */

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

            /*
            |--------------------------------------------------------------------------
            | Presenter pricing
            |--------------------------------------------------------------------------
            */

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

    public function messages(): array
    {
        return [
            'conference_id.required' =>
            'Conference is required.',

            'conference_id.exists' =>
            'The selected conference does not exist.',

            'name.required' =>
            'Registration type name is required.',

            'code.required' =>
            'Registration type code is required.',

            'code.regex' =>
            'Code may only contain lowercase letters, numbers, and underscores.',

            'code.unique' =>
            'This registration type code is already used for this conference.',

            'category.required' =>
            'Category is required.',

            'category.in' =>
            'The selected category is invalid.',

            'fee.required' =>
            'Registration fee is required for participant registration types.',

            'fee.numeric' =>
            'Registration fee must be a number.',

            'fee.min' =>
            'Registration fee cannot be negative.',

            'included_papers.required' =>
            'Included papers is required.',

            'included_papers.integer' =>
            'Included papers must be a whole number.',

            'included_papers.min' =>
            'Included papers cannot be negative.',

            'additional_paper_fee.required' =>
            'Additional paper fee is required.',

            'additional_paper_fee.numeric' =>
            'Additional paper fee must be a number.',

            'additional_paper_fee.min' =>
            'Additional paper fee cannot be negative.',

            'currency.required' =>
            'Currency is required.',

            'is_active.required' =>
            'Status is required.',

            'sort_order.integer' =>
            'Sort order must be a whole number.',

            'sort_order.min' =>
            'Sort order cannot be negative.',
        ];
    }

    public function attributes(): array
    {
        return [
            'conference_id' =>
            'conference',

            'name' =>
            'name',

            'code' =>
            'code',

            'category' =>
            'category',

            'fee' =>
            'registration fee',

            'included_papers' =>
            'included papers',

            'additional_paper_fee' =>
            'additional paper fee',

            'currency' =>
            'currency',

            'description' =>
            'description',

            'benefits' =>
            'benefits',

            'is_active' =>
            'status',

            'sort_order' =>
            'sort order',
        ];
    }
}
