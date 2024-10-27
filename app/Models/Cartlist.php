<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cartlist extends Model
{
    protected $guarded = [];

    function product(){
        return $this->belongsTo(Product::class);
    }

    function product_combination(){
        return $this->belongsTo(Product_combination::class);
    }

    function variation_option(){
        return $this->belongsTo(Variation_option::class);
    }

    function user(){
        return $this->belongsTo(User::class);
    }



}
