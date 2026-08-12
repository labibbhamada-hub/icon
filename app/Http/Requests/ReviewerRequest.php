<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReviewerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $reviewer = $this->route('reviewer');

        $reviewerId = $reviewer?->id;

        return [
            'conference_id' => [
                'required',
                'exists:conferences,id',
            ],

            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('reviewers', 'user_id')
                    ->where(function ($query) {
                        return $query->where(
                            'conference_id',
                            $this->input('conference_id')
                        );
                    })
                    ->ignore($reviewerId),
            ],

            'expertise' => [
                'nullable',
                'string',
            ],

            'institution' => [
                'nullable',
                'string',
                'max:255',
            ],

            'bio' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'conference_id' => 'conference',
            'user_id' => 'reviewer',
            'expertise' => 'expertise',
            'institution' => 'institution',
            'bio' => 'biography',
            'is_active' => 'status',
        ];
    }
}
