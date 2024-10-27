<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courier_company_order extends Model
{
    protected $guarded = [];


    // relationship
    function order(){
        return $this->belongsTo(Order::class);
    }

    function courier_order_bundle(){
        return $this->belongsTo(Courier_order_bundle::class);
    }

    function courier_zone(){
        return $this->belongsTo(Courier_zone::class);
    }

}
