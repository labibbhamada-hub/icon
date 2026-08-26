<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConferencePaymentMethod extends Model
{
    protected $fillable = [
        'conference_id',
        'type',
        'name',
        'provider',
        'account_number',
        'account_name',
        'currency',
        'instructions',
        'qr_code_file',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function conference()
    {
        return $this->belongsTo(
            Conference::class
        );
    }
}
