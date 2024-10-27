<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission_group extends Model
{
    protected $guarded = [];

    // relationship
    function permission_label(){
        return $this->belongsTo(Permission_label::class);
    }
    function permissions(){
        return $this->hasMany(Permission::class);
    }
}
