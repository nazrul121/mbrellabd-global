<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Group_meta extends Model
{
    protected $guarded = [];

    function group(){
        return $this->belongsTo(Group::class);
    }
}
