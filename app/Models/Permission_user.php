<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission_user extends Model
{
    protected $guarded = [];
    protected $table = 'permission_user';

    // relationship
    function permissions(){
        return $this->belongsToMany(Permission::class);
    }

}
