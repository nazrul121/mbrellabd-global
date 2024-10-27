<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment_type extends Model
{
    protected $guarded = [];

    // relationship
    function order_payments(){
        return $this->hasMany(Order_payment::class);
    }
}
