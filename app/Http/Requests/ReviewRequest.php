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
        /*
        |--------------------------------------------------------------------------
        | Assign Reviewer
        |--------------------------------------------------------------------------
        */

        if ($this->route('submission')) {

            $submission = $this->route('submission');

            $reviewStage = $submission->submission_stage;

            $currentRound =
                \App\Models\Review::where(
                    'submission_id',
                    $submission->id
                )
                ->where(
                    'review_stage',
                    $reviewStage
                )
                ->max('review_round');

            $currentRound =
                $currentRound ?: 1;

            $previousStageReviewerIds =
                \App\Models\Review::where(
                    'submission_id',
                    $submission->id
                )
                ->where(
                    'review_stage',
                    '!=',
                    $reviewStage
                )
                ->pluck('reviewer_id')
                ->all();

            return [

                'reviewer_id' => [

                    'required',

                    Rule::exists(
                        'reviewers',
                        'id'
                    )
                        ->where(function ($query) use ($submission) {

                            $query
                                ->where(
                                    'conference_id',
                                    $submission->conference_id
                                )
                                ->where(
                                    'is_active',
                                    true
                                );
                        }),

                    Rule::notIn(
                        $previousStageReviewerIds
                    ),

                    Rule::unique(
                        'reviews',
                        'reviewer_id'
                    )
                        ->where(function ($query) use (
                            $submission,
                            $reviewStage,
                            $currentRound
                        ) {

                            return $query
                                ->where(
                                    'submission_id',
                                    $submission->id
                                )
                                ->where(
                                    'review_stage',
                                    $reviewStage
                                )
                                ->where(
                                    'review_round',
                                    $currentRound
                                );
                        }),

                ],

            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Submit Review
        |--------------------------------------------------------------------------
        */

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

            'reviewer_id.required' =>
            'Reviewer is required.',

            'reviewer_id.exists' =>
            'The selected reviewer is not active or does not belong to this conference.',

            'reviewer_id.unique' =>
            'This reviewer has already been assigned in the current review round.',

            'reviewer_id.not_in' =>
            'This reviewer has already reviewed this submission in another stage and cannot be assigned again.',

            'score.required' =>
            'Score is required.',

            'score.numeric' =>
            'Score must be a number.',

            'score.min' =>
            'Score cannot be less than 0.',

            'score.max' =>
            'Score cannot be greater than 100.',

            'comment.required' =>
            'Review comment is required.',

            'recommendation.required' =>
            'Recommendation is required.',

        ];
    }


    public function attributes(): array
    {
        return [

            'reviewer_id' =>
            'reviewer',

            'score' =>
            'score',

            'comment' =>
            'review comment',

            'recommendation' =>
            'recommendation',

        ];
    }
}
