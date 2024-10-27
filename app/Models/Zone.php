<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $guarded = [];

    // relationship
    function cities(){
        return $this->belongsToMany(City::class);
    }

    function orders(){
        return $this->hasMany(Order::class);
    }
}
