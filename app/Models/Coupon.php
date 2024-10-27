<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $guarded = [];

    // relationship
    function customers(){
        return $this->belongsToMany(Customer::class);
    }
    function coupon_type(){
        return $this->belongsTo(Coupon_type::class);
    }
}
