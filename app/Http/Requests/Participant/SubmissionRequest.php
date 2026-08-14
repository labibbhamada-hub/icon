<?php

namespace App\Http\Requests\Participant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $participant = auth()->user()
            ->participants()
            ->where('registration_status', 'confirmed')
            ->find($this->input('participant_id'));

        return [
            'participant_id' => [
                'required',
                Rule::exists('participants', 'id')
                    ->where(function ($query) {
                        $query
                            ->where('user_id', auth()->id())
                            ->where(
                                'registration_status',
                                'confirmed'
                            );
                    }),
            ],

            'topic_id' => [
                'required',
                Rule::exists('topics', 'id')
                    ->where(function ($query) use ($participant) {

                        if (!$participant) {
                            return;
                        }

                        $query->where(
                            'conference_id',
                            $participant->conference_id
                        )->where(
                            'is_active',
                            true
                        );
                    }),
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'abstract' => [
                'required',
                'string',
            ],

            'keywords' => [
                'required',
                'string',
            ],

            'paper_file' => [
                'required',
                'file',
                'mimes:pdf',
                'max:10240',
            ],

            'authors' => [
                'required',
                'array',
                'min:1',
            ],

            'authors.*.name' => [
                'required',
                'string',
                'max:255',
            ],

            'authors.*.email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'authors.*.institution' => [
                'nullable',
                'string',
                'max:255',
            ],

            'authors.*.department' => [
                'nullable',
                'string',
                'max:255',
            ],

            'authors.*.is_corresponding' => [
                'nullable',
                'boolean',
            ],

            'authors.*.sort_order' => [
                'nullable',
                'integer',
                'min:1',
            ],
        ];
    }

    public function after(): array
    {
        return [
            function ($validator) {
                $authors = $this->input('authors', []);

                $correspondingCount = collect($authors)
                    ->filter(function ($author) {
                        return !empty($author['is_corresponding']);
                    })
                    ->count();

                if ($correspondingCount !== 1) {
                    $validator->errors()->add(
                        'authors',
                        'Exactly one corresponding author is required.'
                    );
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'participant_id.required' =>
            'Registration is required.',

            'participant_id.exists' =>
            'You must have a confirmed registration for this conference.',

            'topic_id.required' =>
            'Topic is required.',

            'topic_id.exists' =>
            'The selected topic is not available for this conference.',

            'paper_file.required' =>
            'Paper file is required.',

            'paper_file.mimes' =>
            'Paper file must be a PDF.',

            'paper_file.max' =>
            'Paper file may not be larger than 10 MB.',

            'authors.required' =>
            'At least one author is required.',
        ];
    }
}
