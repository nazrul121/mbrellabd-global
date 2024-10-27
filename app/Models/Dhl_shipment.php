<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dhl_shipment extends Model
{
    protected $guarded = [];

    function order(){
        return $this->belongsTo(Order::class);
    }
}
