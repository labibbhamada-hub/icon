<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'participant_id',
        'payment_method_id',
        'payment_code',
        'amount',
        'proof_file',
        'status',
        'notes',
        'paid_at',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function participant()
    {
        return $this->belongsTo(
            Participant::class
        );
    }

    public function paymentMethod()
    {
        return $this->belongsTo(
            ConferencePaymentMethod::class,
            'payment_method_id'
        );
    }

    public function verifier()
    {
        return $this->belongsTo(
            User::class,
            'verified_by'
        );
    }
}
