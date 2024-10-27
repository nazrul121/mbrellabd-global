<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $guarded = [];

    // relationship
    function permission_label(){
        return $this->belongsTo(Permission_label::class);
    }

    function permission_group(){
        return $this->belongsTo(Permission_group::class);
    }

    function users(){
        return $this->belongsToMany(User::class);
    }

    function user_types(){
        return $this->belongsToMany(User_type::class);
    }

}
