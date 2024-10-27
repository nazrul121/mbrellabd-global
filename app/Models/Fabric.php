<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fabric extends Model
{
    protected $guarded = [];

    // relationship
    function product(){
        return $this->belongsTo(Product::class);
    }
}