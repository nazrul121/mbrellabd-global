<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product_variation_option extends Model
{
    protected $guarded = [];
    protected $table = 'product_variation_option';

    // relationship
    function product(){
        return $this->belongsTo(Product::class);
    }

    function variation(){
        return $this->belongsTo(Variation::class);
    }

    function variation_option(){
        return $this->belongsTo(Variation_option::class);
    }
}
