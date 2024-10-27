<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    // relationship

    function user(){
        return $this->belongsTo(User::class);
    }
    function orders(){
        return $this->hasMany(Order::class);
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

    function coupons(){
        return $this->belongsToMany(Coupon::class);
    }

    function shipping_addresses(){
        return $this->hasMany(Shipping_address::class);
    }
}
