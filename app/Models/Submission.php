<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'conference_id',
        'participant_id',
        'topic_id',
        'submission_code',
        'title',
        'abstract',
        'keywords',
        'paper_file',
        'revised_file',
        'camera_ready_file',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function authors()
    {
        return $this->hasMany(SubmissionAuthor::class)->orderBy('sort_order');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
