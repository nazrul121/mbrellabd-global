<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product_video extends Model
{
    protected $guarded = [];

    function product(){
        return $this->belongsTo(Product::class);
    }
}
