<?php

namespace App\Http\Requests\Participant;

use Illuminate\Foundation\Http\FormRequest;

class CameraReadyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'camera_ready_file' => [
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
            'camera_ready_file.required' => 'Camera-ready file is required.',
            'camera_ready_file.mimes' => 'Camera-ready file must be a PDF file.',
            'camera_ready_file.max' => 'Camera-ready file may not be larger than 10 MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'camera_ready_file' => 'camera-ready paper',
        ];
    }
}
