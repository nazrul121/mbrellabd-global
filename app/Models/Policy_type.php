<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Policy_type extends Model
{
    protected $guarded = [];


    // relationship

    function policies(){
        return $this->hasMany(Policy::class);
    }
}
