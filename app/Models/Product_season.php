<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product_season extends Model
{
    protected $guarded = [];
    protected $table = 'product_season';

    //relatinship

    function group(){
        return $this->belongsTo(Group::class);
    }

    function inner_group(){
        return $this->belongsTo(Inner_group::class);
    }

    function child_group(){
        return $this->belongsTo(Child_group::class);
    }

    function product(){
        return $this->belongsTo(Product::class);
    }

    function season(){
        return $this->belongsTo(Season::class);
    }

}
