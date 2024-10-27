<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }



    // relationship
    function user_type(){
        return $this->belongsTo(User_type::class);
    }

    function admin(){
        return $this->hasOne(Admin::class);
    }

    function staff(){
        return $this->hasOne(Staff::class);
    }

    function customer(){
        return $this->hasOne(Customer::class);
    }

    function supplier(){
        return $this->hasOne(Supplier::class);
    }

    function products(){
        return $this->belongsToMany(Product::class);
    }

    function permissions(){
        return $this->belongsToMany(Permission::class);
    }

    function permission_users(){
        return $this->belongsToMany(Permission_user::class);
    }
}
