<?php

namespace App\Http\Requests;

use App\Models\Participant;
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
        return [
            'conference_id' => [
                'required',
                'exists:conferences,id',
            ],

            'participant_id' => [
                'required',
                Rule::exists('participants', 'id')
                    ->where(function ($query) {
                        $query->where(
                            'conference_id',
                            $this->input('conference_id')
                        );
                    }),
            ],

            'topic_id' => [
                'required',
                Rule::exists('topics', 'id')
                    ->where(function ($query) {
                        $query->where(
                            'conference_id',
                            $this->input('conference_id')
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
                $this->isMethod('post') ? 'required' : 'nullable',
                'file',
                'mimes:pdf',
                'max:10240',
            ],

            'status' => [
                'required',
                Rule::in([
                    'draft',
                    'submitted',
                    'under_review',
                    'revision',
                    'accepted',
                    'rejected',
                    'camera_ready',
                    'published',
                ]),
            ],

            'submitted_at' => [
                'nullable',
                'date',
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
                'min:0',
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
            'conference_id.required' => 'Conference is required.',
            'participant_id.required' => 'Submitter is required.',
            'participant_id.exists' => 'The selected participant does not belong to the selected conference.',
            'topic_id.required' => 'Topic is required.',
            'topic_id.exists' => 'The selected topic does not belong to the selected conference.',
            'paper_file.required' => 'Paper file is required.',
            'paper_file.mimes' => 'Paper file must be a PDF.',
            'paper_file.max' => 'Paper file may not be larger than 10 MB.',
            'authors.required' => 'At least one author is required.',
            'authors.min' => 'At least one author is required.',
            'authors.*.name.required' => 'Author name is required.',
        ];
    }

    public function attributes(): array
    {
        return [
            'conference_id' => 'conference',
            'participant_id' => 'participant',
            'topic_id' => 'topic',
            'title' => 'paper title',
            'abstract' => 'abstract',
            'keywords' => 'keywords',
            'paper_file' => 'paper file',
            'status' => 'status',
            'submitted_at' => 'submission date',
            'authors' => 'authors',
            'authors.*.name' => 'author name',
            'authors.*.email' => 'author email',
            'authors.*.institution' => 'author institution',
            'authors.*.department' => 'author department',
        ];
    }
}
