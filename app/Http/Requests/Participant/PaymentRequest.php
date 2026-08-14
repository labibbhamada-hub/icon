<?php

namespace App\Http\Requests\Participant;

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
        return [
            'participant_id' => [
                'required',
                Rule::exists('participants', 'id')
                    ->where(function ($query) {
                        $query->where(
                            'user_id',
                            auth()->id()
                        );
                    }),
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'payment_method' => [
                'required',
                Rule::in([
                    'bank_transfer',
                    'cash',
                    'other',
                ]),
            ],

            'proof_file' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf,webp',
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

    public function attributes(): array
    {
        return [
            'participant_id' => 'participant',
            'amount' => 'payment amount',
            'payment_method' => 'payment method',
            'proof_file' => 'payment proof',
            'paid_at' => 'payment date',
            'notes' => 'notes',
        ];
    }
}
