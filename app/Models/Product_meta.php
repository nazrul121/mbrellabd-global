<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product_meta extends Model
{
    protected $guarded = [];

    function product(){
        return $this->belongsTo(Product::class);
    }
}
