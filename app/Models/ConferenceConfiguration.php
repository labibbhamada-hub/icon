<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConferenceConfiguration extends Model
{
    protected $fillable = [
        'conference_id',
        'logo',
        'signature_file',
        'chair_name',
        'chair_title',
    ];

    public function conference()
    {
        return $this->belongsTo(
            Conference::class
        );
    }
}
