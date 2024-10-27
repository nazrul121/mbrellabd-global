<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City_zone extends Model
{
    protected $guarded = [];
    protected $table = 'city_zone';

    function city(){
        return $this->belongsTo(City::class);
    }

    function zone(){
        return $this->belongsTo(Zone::class);
    }
}
