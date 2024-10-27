<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    protected $guarded = [];

    function policy_type(){
        return $this->belongsTo(Policy_type::class);
    }
}
