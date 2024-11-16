<?php

use App\Models\Meta;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metas', function (Blueprint $table) {
            $table->id();
            $table->string('pageFor');
            $table->string('type');
            $table->text('description');
            $table->timestamps();
        });

        Meta::create(['pageFor'=>'home', 'type'=>'title','description'=>'Brand Mbrella is owned by Mondol Group. 
        Mondol Group has long history of exporting world-class readymade garments throughout the globe. 
        In the year 2017, Mbrella starts it’s journey in the BANGLADESH lifestyle retail industry with its true international quality, designs, and fabrics. 
        With the slogan of ‘Embrace The Amazing Mbrella planned to outspread its  outlets across the nationwide. Indigenous tradition, modern design, good quality, reasonable price & comfortable clothes are the reasons Mbrella fashion brand is very popular amongst all levels of rural to upper-class. We are the source of comfort, confidence, vogue, and addiction through our fabrics. Capturing the reliance from our customers will be the prior mission.']);

        Meta::create(['pageFor'=>'wishlist', 'type'=>'title','description'=>'Your wishlist']);
        Meta::create(['pageFor'=>'my-cart', 'type'=>'title','description'=>'Your cart list']);
        Meta::create(['pageFor'=>'login', 'type'=>'title','description'=>'login to continue']);
        Meta::create(['pageFor'=>'register', 'type'=>'title','description'=>'Be with Mbrella ltd']);
        Meta::create(['pageFor'=>'products', 'type'=>'title','description'=>'Be with Mbrella ltd. choose your liked one and order']);
        Meta::create(['pageFor'=>'check-out', 'type'=>'title','description'=>'Be with Mbrella ltd. choose your liked one and order']);
    
        Meta::create(['pageFor'=>'career', 'type'=>'title','description'=>'Be with Mbrella ltd. choose your liked one and order']);
        Meta::create(['pageFor'=>'blog', 'type'=>'title','description'=>'Be with Mbrella ltd. choose your liked one and order']);
        Meta::create(['pageFor'=>'showroom', 'type'=>'title','description'=>'Be with Mbrella ltd. choose your liked one and order']);
        Meta::create(['pageFor'=>'faq', 'type'=>'title','description'=>'Be with Mbrella ltd. choose your liked one and order']);
        Meta::create(['pageFor'=>'track', 'type'=>'title','description'=>'Be with Mbrella ltd. choose your liked one and order']);
        Meta::create(['pageFor'=>'categories', 'type'=>'title','description'=>'Shop with categories']);
        // 'order-placed', 'invoice'
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metas');
    }
}
