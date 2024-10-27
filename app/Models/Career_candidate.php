<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Career_candidate extends Model
{
    protected $guarded = [];

    function career(){
        return $this->belongsTo(Career::class);
    }
}
