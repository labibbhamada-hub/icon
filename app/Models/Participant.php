<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = [
        'conference_id',
        'registration_number',
        'full_name',
        'email',
        'phone',
        'institution',
        'department',
        'country',
        'city',
        'participant_type',
        'attendance_type',
        'registration_status',
        'notes',
        'registered_at',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
    ];

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }
}