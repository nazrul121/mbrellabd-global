<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon_type extends Model
{
    protected $guarded = [];

    function coupons(){
        return $this->hasMany(Coupon::class);
    }
}
