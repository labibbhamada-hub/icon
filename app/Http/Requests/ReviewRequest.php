<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->route('submission')) {
            return [
                'reviewer_id' => [
                    'required',
                    Rule::exists('reviewers', 'id')
                        ->where(function ($query) {
                            $query
                                ->where(
                                    'conference_id',
                                    $this->route('submission')->conference_id
                                )
                                ->where('is_active', true);
                        }),

                    Rule::unique('reviews', 'reviewer_id')
                        ->where(function ($query) {
                            return $query->where(
                                'submission_id',
                                $this->route('submission')->id
                            );
                        }),
                ],
            ];
        }

        return [
            'score' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'comment' => [
                'required',
                'string',
            ],

            'recommendation' => [
                'required',
                Rule::in([
                    'accept',
                    'minor_revision',
                    'major_revision',
                    'reject',
                ]),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'reviewer_id.required' => 'Reviewer is required.',
            'reviewer_id.exists' => 'The selected reviewer is not active or does not belong to this conference.',
            'reviewer_id.unique' => 'This reviewer has already been assigned to this submission.',
            'score.required' => 'Score is required.',
            'score.numeric' => 'Score must be a number.',
            'score.min' => 'Score cannot be less than 0.',
            'score.max' => 'Score cannot be greater than 100.',
            'comment.required' => 'Review comment is required.',
            'recommendation.required' => 'Recommendation is required.',
        ];
    }

    public function attributes(): array
    {
        return [
            'reviewer_id' => 'reviewer',
            'score' => 'score',
            'comment' => 'review comment',
            'recommendation' => 'recommendation',
        ];
    }
}
