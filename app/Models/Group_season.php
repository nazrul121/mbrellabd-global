<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group_season extends Model
{
    protected $guarded = [];
    protected $table = 'group_season';

    function group(){
        return $this->belongsTo(Group::class);
    }

    function season(){
        return $this->belongsTo(Season::class);
    }
}
