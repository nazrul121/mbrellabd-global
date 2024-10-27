<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Child_group_meta extends Model
{
    protected $guarded = [];

    function child_group(){
        return $this->belongsTo(Child_group::class);
    }
}
