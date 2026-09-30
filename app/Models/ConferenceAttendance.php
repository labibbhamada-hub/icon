<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConferenceAttendance extends Model
{
    protected $fillable = [
        'conference_id',
        'participant_id',
        'checked_in_at',
        'attendance_status',
        'verified_at',
        'verified_by',
        'verification_notes',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
