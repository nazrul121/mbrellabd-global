<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Temp_variant extends Model
{
    protected $guarded = [];

    function variant_table(){
        return $this->belongsTo(Variant_table::class);
    }

    function product(){
        return $this->belongsTo(Product::class);
    }
}
