<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $guarded = [];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function getRolesWithoutPivotAttribute()
    {
        return $this->roles->makeHidden('pivot');
    }



    
    public function groups(){
        return $this->belongsToMany(Group::class);
    }

    public function inner_groups(){
        return $this->belongsToMany(Inner_roup::class);
    }

    public function child_groups(){
        return $this->belongsToMany(Child_roup::class);
    }

    public function products(){
        return $this->belongsToMany(Product::class);
    }

    public function highlights(){
        return $this->belongsToMany(Highlight::class);
    }

    public function seasons(){
        return $this->belongsToMany(Season::class);
    }
    public function invoice_discounts(){
        return $this->belongsToMany(Invoice_discount::class);
    }

    public function promotions(){
        return $this->belongsToMany(Promotion::class);
    }
    public function videos(){
        return $this->belongsToMany(Video::class);
    }
    public function sliders(){
        return $this->belongsToMany(Slider::class);
    }
    public function testimonials(){
        return $this->belongsToMany(Testimonial::class);
    }

    public function quick_services(){
        return $this->belongsToMany(Quick_service::class);
    }
    public function blogs(){
        return $this->belongsToMany(Blog::class);
    }
    public function faqs(){
        return $this->belongsToMany(Faq::class);
    }
    function careers(){
        return $this->belongsToMany(Career::class);
    }


    function orders(){
        return $this->hasMany(Order::class);
    }
}
