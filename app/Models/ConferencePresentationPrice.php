<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConferencePresentationPrice extends Model
{
    protected $fillable = [
        'registration_type_id',
        'presentation_type',
        'fee',
        'currency',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'fee' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function registrationType()
    {
        return $this->belongsTo(
            ConferenceRegistrationType::class,
            'registration_type_id'
        );
    }
}
