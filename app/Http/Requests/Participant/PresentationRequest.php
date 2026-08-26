<?php

namespace App\Http\Requests\Participant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PresentationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'presentation_type' => [
                'required',
                Rule::in([
                    'oral',
                    'poster',
                ]),
            ],

            'presentation_mode' => [
                'required',
                Rule::in([
                    'offline',
                    'online',
                ]),
            ],

            'presenter_author_id' => [
                'required',
                'integer',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'presentation_type.required' =>
            'Please select a presentation type.',

            'presentation_mode.required' =>
            'Please select a presentation mode.',

            'presenter_author_id.required' =>
            'Please select the author who will present this paper.',
        ];
    }
}
