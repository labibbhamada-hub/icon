<?php

namespace App\Http\Requests\Participant;

use Illuminate\Foundation\Http\FormRequest;

class FullPaperSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'paper_file' => [
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
            'paper_file.required' =>
            'Full paper file is required.',

            'paper_file.file' =>
            'The full paper file must be a valid file.',

            'paper_file.mimes' =>
            'The full paper must be a PDF file.',

            'paper_file.max' =>
            'The full paper file may not be greater than 10 MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'paper_file' => 'full paper',
        ];
    }
}
