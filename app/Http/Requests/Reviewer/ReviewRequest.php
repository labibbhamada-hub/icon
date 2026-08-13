<?php

namespace App\Http\Requests\Reviewer;

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
            'score' => 'score',
            'comment' => 'review comment',
            'recommendation' => 'recommendation',
        ];
    }
}
