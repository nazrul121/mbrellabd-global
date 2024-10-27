<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variation_option extends Model
{
    protected $guarded = [];


    // relationship
    function variation(){
        return $this->belongsTo(Variation::class);
    }

    function product_variation_options(){
        return $this->hasMany(Product_variation_option::class);
    }

}
