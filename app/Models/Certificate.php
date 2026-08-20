<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'participant_id',
        'conference_id',
        'submission_id',
        'certificate_number',
        'type',
        'file_path',
        'issued_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }
}
