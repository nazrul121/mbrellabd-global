<?php

use App\Models\Coupon_type;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_types', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamps();

        });

        $datas = array('One time purchase','Up to expiry date','fix amount for one time purchase');
        foreach($datas as $data){
            Coupon_type::create([
                'title'=>$data
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupon_types');
    }
}
