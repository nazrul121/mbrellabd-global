<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Child_group_season_product extends Model
{
    protected $guarded = [];
    protected $table = 'child_group_season_product';

    // relationship
    function product(){
        return $this->belongsTo(Product::class);
    }

    function inner_group_season(){
        return $this->belongsTo(Inner_group_season::class);
    }
}
