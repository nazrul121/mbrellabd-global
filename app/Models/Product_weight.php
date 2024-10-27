<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product_weight extends Model
{

    protected $guarded = [];

    // relationship
    function product(){
        return $this->belongsTo(Product::class);
    }
    function group(){
        return $this->belongsTo(Group::class);
    }

    function inner_group(){
        return $this->belongsTo(Inner_group::class);
    }
    function child_group(){
        return $this->belongsTo(Child_group::class);
    }




}
