<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportantDate extends Model
{
    protected $fillable = [
        'conference_id',
        'title',
        'type',
        'description',
        'date',
        'end_date',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }
}
