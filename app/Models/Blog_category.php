<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog_category extends Model
{
    protected $guarded = [];

    function blogs(){
        return $this->belongsToMany(Blog::class);
    }

}
