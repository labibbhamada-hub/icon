<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConferenceConfiguration extends Model
{
    protected $fillable = [
        'conference_id',
        'logo',
        'signature_file',
        'bank_name',
        'account_number',
        'account_name',
        'regular_fee',
        'student_fee',
        'chair_name',
        'chair_title',
    ];

    protected $casts = [
        'regular_fee' => 'decimal:2',
        'student_fee' => 'decimal:2',
    ];

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }
}
