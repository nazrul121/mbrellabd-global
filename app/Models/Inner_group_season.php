<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inner_group_season extends Model
{
    protected $guarded = [];
    protected $table = 'inner_group_season';

    function group(){
        return $this->belongsTo(Group::class);
    }

    function inner_group(){
        return $this->belongsTo(Inner_group::class);
    }

    function season(){
        return $this->belongsTo(Season::class);
    }

}
