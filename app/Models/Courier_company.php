<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courier_company extends Model
{
    protected $guarded = [];


    // relationship
    function courier_representatives(){
        return $this->hasMany(Courier_representative::class);
    }

    function courier_order_bundles(){
        return $this->hasMany(Courier_order_bundle::class);
    }

    function courier_zones(){
        return $this->hasMany(Courier_zone::class);
    }
   
}
