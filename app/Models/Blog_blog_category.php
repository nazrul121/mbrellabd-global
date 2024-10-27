<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog_blog_category extends Model
{
    protected $guarded = [];
    protected $table = 'blog_blog_category';

    function blog(){
        return $this->belongsTo(Blog::class);
    }

    function blog_category(){
        return $this->belongsTo(Blog_category::class);
    }
}
