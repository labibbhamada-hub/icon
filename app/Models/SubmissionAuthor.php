<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionAuthor extends Model
{
    protected $fillable = [
        'submission_id',
        'name',
        'email',
        'institution',
        'department',
        'is_corresponding',
        'sort_order',
    ];

    protected $casts = [
        'is_corresponding' => 'boolean',
    ];

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }
}
