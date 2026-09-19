<?php

namespace App\Http\Requests\Participant;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VideoSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        |
        | Ownership and registration status are enforced by the
        | VideoSubmissionController.
        |
        | This request class is responsible for validating the submitted
        | video link and presenter author.
        |
        */

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $submission = $this->route('submission');

        return [
            'presenter_author_id' => [
                'required',
                'integer',

                Rule::exists(
                    'submission_authors',
                    'id'
                )->where(function ($query) use ($submission) {
                    $query->where(
                        'submission_id',
                        $submission?->id
                    );
                }),
            ],

            'video_url' => [
                'required',
                'url',
                'regex:/^https:\/\/drive\.google\.com\//i',
                'max:2048',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'presenter_author_id.required' =>
            'Please select the author who will present this paper.',

            'presenter_author_id.exists' =>
            'The selected presenter is not an author of this paper.',

            'video_url.required' =>
            'Google Drive video link is required.',

            'video_url.url' =>
            'Please enter a valid video URL.',

            'video_url.regex' =>
            'The video link must be a Google Drive URL using HTTPS.',

            'video_url.max' =>
            'The video link may not be longer than 2048 characters.',
        ];
    }
}
