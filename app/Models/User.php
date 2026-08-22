<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
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
        'email_verified_at' => 'datetime',
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
