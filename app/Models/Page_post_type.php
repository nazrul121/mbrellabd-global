<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page_post_type extends Model
{
    protected $guarded = [];

     // relatinship
     function page_posts(){
        return $this->hasMany(Page_post::class);
    }
}
