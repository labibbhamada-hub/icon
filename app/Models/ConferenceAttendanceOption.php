<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConferenceAttendanceOption extends Model
{
    protected $fillable = [
        'conference_id',
        'type',
        'sort_order',
    ];

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }
}
