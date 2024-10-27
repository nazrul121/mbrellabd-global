<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variation_option_photo extends Model
{
    protected $guarded = [];

    // relationship
    function product(){
        return $this->belongsTo(Product::class);
    }

    function variation(){
        return $this->belongsTo(Variation::class);
    }

    function variation_option(){
        return $this->belongsTo(Variation_option::class);
    }
}
