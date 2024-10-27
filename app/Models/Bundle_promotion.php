<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bundle_promotion extends Model
{
    protected $guarded = [];

    function bundle_promotion_products(){
        return $this->hasMany(Bundle_promotion_product::class);
    }
}
