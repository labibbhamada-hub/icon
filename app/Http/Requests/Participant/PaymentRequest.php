<?php

namespace App\Http\Requests\Participant;

use App\Models\Participant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $participant = Participant::with([
            'conference',
        ])
            ->where(
                'id',
                $this->input('participant_id')
            )
            ->where(
                'user_id',
                auth()->id()
            )
            ->first();

        return [
            'participant_id' => [
                'required',

                Rule::exists(
                    'participants',
                    'id'
                )->where(function ($query) {
                    $query->where(
                        'user_id',
                        auth()->id()
                    );
                }),
            ],

            'payment_method_id' => [
                'required',

                'integer',

                Rule::exists(
                    'conference_payment_methods',
                    'id'
                )->where(function ($query) use ($participant) {
                    if (!$participant) {
                        $query->whereRaw(
                            '1 = 0'
                        );

                        return;
                    }

                    $query
                        ->where(
                            'conference_id',
                            $participant->conference_id
                        )
                        ->where(
                            'is_active',
                            true
                        );
                }),
            ],

            'proof_file' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:5120',
            ],

            'paid_at' => [
                'required',
                'date',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'participant_id.required' =>
            'Registration is required.',

            'participant_id.exists' =>
            'The selected registration does not belong to your account.',

            'payment_method_id.required' =>
            'Please select a payment method.',

            'payment_method_id.exists' =>
            'The selected payment method is not available for this registration.',

            'proof_file.required' =>
            'Payment proof is required.',

            'proof_file.mimes' =>
            'Payment proof must be a JPG, JPEG, PNG, WebP, or PDF file.',

            'proof_file.max' =>
            'Payment proof may not be larger than 5 MB.',

            'paid_at.required' =>
            'Payment date is required.',
        ];
    }
}
