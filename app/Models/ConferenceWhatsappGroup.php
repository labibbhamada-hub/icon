<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConferenceWhatsappGroup extends Model
{
    protected $fillable = [
        'conference_id',
        'title',
        'group_url',
        'description',
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
