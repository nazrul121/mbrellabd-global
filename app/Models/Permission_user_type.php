<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission_user_type extends Model
{
    protected $guarded = [];
    protected $table = 'permission_user_type';

    // relationship
    function permissions(){
        return $this->belongsToMany(Permission::class);
    }

}
