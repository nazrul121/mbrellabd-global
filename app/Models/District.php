<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $guarded = [];

    function division(){
        return $this->belongsTo(Division::class);
    }

    function cities(){
        return $this->hasMany(City::class);
    }

    function show_rooms(){
        return $this->hasMany(Show_room::class);
    }
}
