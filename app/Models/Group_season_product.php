<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group_season_product extends Model
{
    protected $guarded = [];
    protected $table = 'group_season_product';

    // relationship
    function product(){
        return $this->belongsTo(Product::class);
    }

    function group_season(){
        return $this->belongsTo(Group_season::class);
    }
}
