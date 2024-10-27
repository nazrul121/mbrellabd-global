<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Child_group_country extends Model
{
    protected $guarded = [];
    protected $table = 'child_group_country';

    function child_gorup(){
        return $this->belongsTo(Child_group::class);
    }
    function country(){
        return $this->belongsTo(Country::class);
    }
}
