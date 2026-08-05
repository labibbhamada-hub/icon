<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConferenceSetting extends Model
{
    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }
}
