<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bundle_promotion_product extends Model
{
    protected $guarded = [];
    protected $table = 'bundle_promotion_product';


    // relationship
    function product(){
        return $this->belongsTo(Product::class);
    }
    function bundle_promotion(){
        return $this->belongsTo(Bundle_promotion::class);
    }
}
