<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $guarded = [];

    // relationship
    function products(){
        return $this->hasMany(Product::class);
    }
    function division(){
        return $this->belongsTo(Division::class);
    }
    function district(){
        return $this->belongsTo(District::class);
    }
    function city(){
        return $this->belongsTo(City::class);
    }
}
