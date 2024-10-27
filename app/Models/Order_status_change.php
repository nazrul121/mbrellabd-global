<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_status_change extends Model
{
    protected $guarded = [];

    function order(){
        return $this->belongsTo(Order::class);
    }

    function order_status(){
        return $this->belongsTo(Order_status::class);
    }

    function user(){
        return $this->belongsTo(User::class);
    }
}
