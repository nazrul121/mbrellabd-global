<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inner_group_product extends Model
{
    protected $table = 'inner_group_product';
    protected $guarded = [];

    //relationship
    function product(){
        return $this->belongsTo(Product::class);
    }
    function inner_group(){
        return $this->belongsTo(Inner_group::class);
    }
}
