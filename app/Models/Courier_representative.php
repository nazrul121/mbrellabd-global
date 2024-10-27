<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courier_representative extends Model
{
    protected $guarded = [];

    // relationship
    function courier_company(){
        return $this->belongsTo(Courier_company::class);
    }

    function courier_oder_bundles(){
        return $this->hasMany(Courier_order_bundle::class);
    }
}
