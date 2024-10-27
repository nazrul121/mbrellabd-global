<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Child_group_season extends Model
{
    protected $guarded = [];
    protected $table = 'child_group_season';


    // relationship
    function child_group(){
        return $this->belongsTo(Child_group::class);
    }

    function inner_group(){
        return $this->belongsTo(Inner_group::class);
    }

    function season(){
        return $this->belongsTo(Season::class);
    }
}
