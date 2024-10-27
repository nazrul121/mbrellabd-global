<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    protected $guarded = [];

    function career_candidates(){
        return $this->hasMany(Career_candidate::class);
    }
    function countries(){
        return $this->belongsToMany(Country::class);
    }
    
}
