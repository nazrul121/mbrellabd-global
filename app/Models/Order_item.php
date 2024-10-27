<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_item extends Model
{
    protected $guarded = [];

    // relationship
    function order(){
        return $this->belongsTo(Order::class);
    }

    function campaign(){
        return $this->belongsTo(Campaign::class);
    }

    function promotion(){
        return $this->belongsTo(Promotion::class);
    }

    function product(){
        return $this->belongsTo(Product::class);
    }

    function product_combination(){
        return $this->belongsTo(Product_combination::class);
    }
}
