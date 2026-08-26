<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConferenceRegistrationType extends Model
{
    protected $fillable = [
        'conference_id',
        'name',
        'code',
        'category',
        'fee',
        'included_papers',
        'additional_paper_fee',
        'currency',
        'description',
        'benefits',
        'is_active',
        'sort_order',
    ];
    protected $casts = [
        'fee' => 'decimal:2',
        'included_papers' => 'integer',
        'additional_paper_fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];
    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }
    public function participants()
    {
        return $this->hasMany(Participant::class, 'registration_type_id');
    }
}
