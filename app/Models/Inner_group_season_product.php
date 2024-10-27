<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inner_group_season_product extends Model
{
    protected $guarded = [];
    protected $table = 'inner_group_season_product';

    // relationship
    function product(){
        return $this->belongsTo(Product::class);
    }

    function inner_group_season(){
        return $this->belongsTo(Inner_group_season::class);
    }
}
