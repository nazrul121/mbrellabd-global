<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page_post extends Model
{
    protected $guarded = [];

    // relatinship
    function page_post_type(){
        return $this->belongsTo(Page_post_type::class);
    }
}
