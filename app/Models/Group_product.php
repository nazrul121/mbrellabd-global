<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group_product extends Model
{
   protected $table = 'group_product';
   protected $guarded = [];

    //relationship
    function product(){
        return $this->belongsTo(Product::class);
    }
    function group(){
        return $this->belongsTo(Group::class);
    }


}
