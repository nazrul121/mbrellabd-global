<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_payment extends Model
{
    protected $guarded = [];

    // relationship
    function payment_type(){
        return $this->belongsTo(Payment_type::class);
    }

    function order(){
        return $this->belongsTo(Order::class);
    }

    function user(){
        return $this->belongsTo(User::class);
    }
}
