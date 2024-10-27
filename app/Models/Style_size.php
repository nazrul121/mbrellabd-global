<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Style_size extends Model
{
    protected $guarded = [];

    // relationship
    function product(){
        return $this->belongsTo(Product::class);
    }
}
