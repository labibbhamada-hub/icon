<?php

namespace App\Http\Requests\Participant;

use Illuminate\Foundation\Http\FormRequest;

class RevisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'revised_file' => [
                'required',
                'file',
                'mimes:pdf',
                'max:10240',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'revised_file.required' => 'Revised paper file is required.',
            'revised_file.mimes' => 'Revised paper must be a PDF file.',
            'revised_file.max' => 'Revised paper may not be larger than 10 MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'revised_file' => 'revised paper',
        ];
    }
}
