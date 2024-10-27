<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission_label extends Model
{
    protected $guarded = [];


    // relationship
    function permissions(){
        return $this->hasMany(Permission::class);
    }

    function permission_groups(){
        return $this->hasMany(Permission_group::class);
    }

}
