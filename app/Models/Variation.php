<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    protected $guarded = [];

    // relationship
    function variation_options(){
        return $this->hasMany(Variation_option::class);
    }

    function product_variation_options(){
        return $this->hasMany(Product_variation_option::class);
    }
}
