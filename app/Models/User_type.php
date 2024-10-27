<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_type extends Model
{
    protected $guarded = [];


    // relationship
    function users(){
        return $this->hasMany(User::class);
    }

    // function permission_user_types(){
    //     return $this->belongsToMany(Permission_user_type::class);
    // }

    function permissions(){
        return $this->belongsToMany(Permission::class);
    }
}
