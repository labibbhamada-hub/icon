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

            'payment_method' => [
                'required',
                Rule::in([
                    'bank_transfer',
                ]),
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
}
