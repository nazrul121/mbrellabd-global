<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Highlight extends Model
{
    protected $guarded = [];

    // relationship
    function products(){
        return $this->belongsToMany(Product::class);
    }

    public function countries(){
        return $this->belongsToMany(Country::class);
    }
}
