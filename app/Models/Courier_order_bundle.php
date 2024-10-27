<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courier_order_bundle extends Model
{
    protected $guarded = [];


    // relationship
    function courier_representative(){
        return $this->belongsTo(Courier_representative::class);
    }

    function courier_company(){
        return $this->belongsTo(Courier_company::class);
    }

    function user(){
        return $this->belongsTo(User::class);
    }


}
