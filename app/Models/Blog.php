<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $guarded = [];


    function blog_categories(){
        return $this->belongsToMany(Blog_category::class);
    }

    public function countries(){
        return $this->belongsToMany(Country::class);
    }
    
}
