<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = [
        'user_id',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}
