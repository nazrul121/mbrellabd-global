<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inner_group_meta extends Model
{
    protected $guarded = [];

    function inner_group(){
        return $this->belongsTo(Inner_group::class);
    }
}
