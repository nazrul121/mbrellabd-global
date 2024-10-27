<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_item_edition extends Model
{
    protected $guarded = [];

    // relationship
    function order_item(){
        return $this->belongsTo(Order_item::class);
    }

    function product(){
        return $this->belongsTo(Product::class);
    }

    function product_combination(){
        return $this->belongsTo(Product_combination::class);
    }
}
