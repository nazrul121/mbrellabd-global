<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping_address extends Model
{
    protected $guarded = [];

    function country(){
        return $this->belongsTo(Country::class);
    }

    function customer(){
        return $this->belongsTo(Customer::class);
    }

    function division(){
        return $this->belongsTo(Division::class);
    }
    function district(){
        return $this->belongsTo(District::class);
    }
    function city(){
        return $this->belongsTo(City::class);
    }
}
