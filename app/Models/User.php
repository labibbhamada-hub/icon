<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function reviewers()
    {
        return $this->hasMany(Reviewer::class);
    }

    public function verifiedPayments()
    {
        return $this->hasMany(
            Payment::class,
            'verified_by'
        );
    }
}
