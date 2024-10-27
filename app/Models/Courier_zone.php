<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courier_zone extends Model
{
    protected $guarded = [];

    function courier_company(){
        return $this->belongsTo(Courier_company::class);
    }

    function courier_company_orders(){
        return $this->hasMany(Courier_company_order::class);
    }
}
