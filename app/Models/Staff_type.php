<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff_type extends Model
{
    protected $guarded = [];


    function staffs(){
        return $this->hasMany(Staff::class);
    }


}
