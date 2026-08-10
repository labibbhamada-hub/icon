<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $fillable = [
        'conference_id',
        'name',
        'type',
        'description',
        'logo',
        'website',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }
}
