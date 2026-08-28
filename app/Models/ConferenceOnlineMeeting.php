<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConferenceOnlineMeeting extends Model
{
    protected $fillable = [
        'conference_id',
        'title',
        'meeting_url',
        'meeting_id',
        'passcode',
        'instructions',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function conference()
    {
        return $this->belongsTo(
            Conference::class
        );
    }
}
