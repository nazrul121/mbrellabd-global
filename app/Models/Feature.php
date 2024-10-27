<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $guarded = [];

    // relationship
    function product(){
        return $this->belongsTo(Product::class);
    }
}
