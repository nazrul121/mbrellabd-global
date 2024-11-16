<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coupon_type_id');
            $table->foreign('coupon_type_id')->references('id')->on('coupon_types');
            $table->string('coupon_code');
            $table->string('title');
            $table->enum('discount_id',['percentage','fix-amount','till-expire'])->nullable();
            $table->string('discount_value')->nullable();
            $table->enum('is_validate',['0','1'])->nullable(1);
            $table->date('expiry_date')->nullable();
            $table->integer('cost');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
