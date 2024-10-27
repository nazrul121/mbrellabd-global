<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon_customer extends Model
{
    protected $guarded = [];
    protected $table = 'coupon_customer';

    // relationship

    function customer(){
        return $this->belongsTo(Customer::class);
    }

    function coupon(){
        return $this->belongsTo(Coupon::class);
    }

}
