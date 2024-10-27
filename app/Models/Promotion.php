<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $guarded = [];

    // relationship
    function promotion_type(){
        return $this->belongsTo(Promotion_type::class);
    }

    function products(){
        return $this->belongsToMany(Product::class);
    }
   
    function groups(){
        return $this->belongsToMany(Group::class);
    }
    function inner_groups(){
        return $this->belongsToMany(Inner_group::class);
    }
    function child_groups(){
        return $this->belongsToMany(Child_group::class);
    }

    function countries(){
        return $this->belongsToMany(Country::class);
    }
}
