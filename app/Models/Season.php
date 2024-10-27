<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    protected $guarded = [];

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
    public function countries(){
        return $this->belongsToMany(Country::class);
    }

}
