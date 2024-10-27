<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $guarded = [];

    function district(){
        return $this->belongsTo(District::class);
    }

    function customers(){
        return $this->hasnM(Customer::class);
    }

    function zones(){
        return $this->belongsToMany(Zone::class);
    }

}
