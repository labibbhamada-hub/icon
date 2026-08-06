<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConferenceSetting extends Model
{
    protected $fillable = [
        'conference_id',
        'is_active',
        'registration_enabled',
        'submission_enabled',
        'payment_enabled',
        'review_enabled',
        'certificate_enabled',
        'published',
        'maintenance_mode',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'registration_enabled' => 'boolean',
        'submission_enabled' => 'boolean',
        'payment_enabled' => 'boolean',
        'review_enabled' => 'boolean',
        'certificate_enabled' => 'boolean',
        'published' => 'boolean',
        'maintenance_mode' => 'boolean',
    ];

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }
}
