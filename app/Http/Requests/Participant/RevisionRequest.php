<?php

namespace App\Http\Requests\Participant;

use App\Models\Submission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RevisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $submission = $this->route('submission');

        if (
            $submission instanceof Submission
            && $submission->submission_stage === 'abstract'
        ) {
            return [
                'title' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'abstract' => [
                    'required',
                    'string',
                    function ($attribute, $value, $fail) {
                        if (!is_string($value)) {
                            return;
                        }

                        $abstract = trim($value);

                        if ($abstract === '') {
                            return;
                        }

                        $words = preg_split(
                            '/[\s\p{Z}]+/u',
                            $abstract,
                            -1,
                            PREG_SPLIT_NO_EMPTY
                        );

                        if ($words === false) {
                            $fail(
                                'The abstract could not be validated. Please check the text encoding.'
                            );

                            return;
                        }

                        if (count($words) > 300) {
                            $fail(
                                'The abstract may not exceed 300 words.'
                            );
                        }
                    },
                ],

                'keywords' => [
                    'required',
                    'string',
                ],

                'authors' => [
                    'required',
                    'array',
                    'min:1',
                ],

                'authors.*.id' => [
                    'nullable',
                    'integer',
                    Rule::exists('submission_authors', 'id')
                        ->where(
                            fn($query) => $query->where(
                                'submission_id',
                                $submission->id
                            )
                        ),
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

        // Full Paper revision continues to require a PDF file.
        return [
            'revised_file' => [
                'required',
                'file',
                'mimes:pdf',
                'max:10240',
            ],
        ];
    }

    public function after(): array
    {
        $submission = $this->route('submission');

        if (
            !$submission instanceof Submission
            || $submission->submission_stage !== 'abstract'
        ) {
            return [];
        }

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
            'title.required' =>
            'Paper title is required.',

            'abstract.required' =>
            'Abstract is required.',

            'keywords.required' =>
            'Keywords are required.',

            'abstract.max' =>
            'The abstract may not exceed 300 words.',

            'authors.required' =>
            'At least one author is required.',

            'authors.min' =>
            'At least one author is required.',

            'authors.*.id.exists' =>
            'The selected author does not belong to this submission.',

            'authors.*.name.required' =>
            'Author name is required.',

            'revised_file.required' =>
            'Revised paper file is required.',

            'revised_file.file' =>
            'Revised paper must be a valid file.',

            'revised_file.mimes' =>
            'Revised paper must be a PDF file.',

            'revised_file.max' =>
            'Revised paper may not be larger than 10 MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'paper title',
            'abstract' => 'abstract',
            'keywords' => 'keywords',
            'revised_file' => 'revised paper',
        ];
    }
}
