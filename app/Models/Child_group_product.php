<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Child_group_product extends Model
{
    protected $table = 'child_group_product';
    protected $guarded = [];

    //relationship
    function product(){
        return $this->belongsTo(Product::class);
    }
    function child_group(){
        return $this->belongsTo(Child_group::class);
    }
}
