<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog_country extends Model
{
    protected $guarded = [];
    protected $table = 'blog_country';


    function blog(){
        return $this->belongsTo(Blog::class);
    }
    function country(){
        return $this->belongsTo(Country::class);
    }
}
