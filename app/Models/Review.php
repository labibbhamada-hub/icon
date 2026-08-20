<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'submission_id',
        'reviewer_id',
        'review_round',
        'score',
        'comment',
        'recommendation',
        'reviewed_at',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(Reviewer::class);
    }

    public function scopeForRound($query, int $round)
    {
        return $query->where(
            'review_round',
            $round
        );
    }
}
