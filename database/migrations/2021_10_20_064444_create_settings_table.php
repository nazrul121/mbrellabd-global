<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('type',100);
            $table->string('value',100);
            $table->timestamps();
        });

        Setting::create(['type'=>'blog-watermark','value'=>'0']);
        Setting::create(['type'=>'blog-weight','value'=>'1400']);
        Setting::create(['type'=>'blog-height','value'=>'960']);

        Setting::create(['type'=>'product-weight','value'=>'1920']);
        Setting::create(['type'=>'product-height','value'=>'1400']);
        Setting::create(['type'=>'product-watermark','value'=>'0']);

        Setting::create(['type'=>'slider-weight','value'=>'1800']);
        Setting::create(['type'=>'slider-height','value'=>'800']);

        Setting::create(['type'=>'deliveryCost_from','value'=>'zone']);
        Setting::create(['type'=>'max-transaction-amount','value'=>'500000']);

        Setting::create(['type'=>'cat-view','value'=>'circle']);

        Setting::create(['type'=>'filter-variation','value'=>'1']);
        Setting::create(['type'=>'variation-at-product-list','value'=>'1']);
        Setting::create(['type'=>'staff-permission-type','value'=>'staff-individual']);
        Setting::create(['type'=>'add-to-cart-status','value'=>'1']);
        Setting::create(['type'=>'send-sms','value'=>'1']);
        
        

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
