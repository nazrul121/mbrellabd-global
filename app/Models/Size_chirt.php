<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size_chirt extends Model
{
    protected $guarded = [];

    function products(){
        return $this->hasMany(Product::class);
    }
}
