<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Show_room extends Model
{
    protected $guarded = [];

    function district(){
        return $this->belongsTo(District::class);
    }
}
