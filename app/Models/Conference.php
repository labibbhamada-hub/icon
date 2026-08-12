<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conference extends Model
{
    protected $fillable = ['name', 'short_name', 'year', 'theme', 'venue', 'city', 'country', 'start_date', 'end_date', 'abstract_deadline', 'fullpaper_deadline', 'registration_deadline', 'logo', 'banner', 'status'];

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

    public function speakers()
    {
        return $this->hasMany(Speaker::class);
    }

    public function partners()
    {
        return $this->hasMany(Partner::class);
    }

    public function importantDates()
    {
        return $this->hasMany(ImportantDate::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function reviewers()
    {
        return $this->hasMany(Reviewer::class);
    }
}
