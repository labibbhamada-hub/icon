<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conference extends Model
{

    
    public function setting()
    {
        return $this->hasOne(ConferenceSetting::class);
    }
}
