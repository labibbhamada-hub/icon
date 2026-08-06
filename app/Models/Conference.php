<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conference extends Model
{
    protected $fillable = [
        'name',
        'short_name',
        'year',
        'theme',
        'venue',
        'city',
        'country',
        'start_date',
        'end_date',
        'abstract_deadline',
        'fullpaper_deadline',
        'registration_deadline',
        'logo',
        'banner',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'abstract_deadline' => 'date',
        'fullpaper_deadline' => 'date',
        'registration_deadline' => 'date',
    ];

    public function setting()
    {
        return $this->hasOne(ConferenceSetting::class);
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
}
