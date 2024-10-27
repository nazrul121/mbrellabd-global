<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $guarded = [];
    protected $table = 'staffs';

    function staff_type(){
        return $this->belongsTo(Staff_type::class);
    }

    function user(){
        return $this->belongsTo(User::class);
    }
}
