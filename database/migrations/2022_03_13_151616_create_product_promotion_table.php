<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPromotionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_promotion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->foreign('group_id')->references('id')->on('groups');

            $table->unsignedBigInteger('inner_group_id')->nullable();
            $table->foreign('inner_group_id')->references('id')->on('inner_groups');

            $table->unsignedBigInteger('child_group_id')->nullable();
            $table->foreign('child_group_id')->references('id')->on('child_groups');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedBigInteger('promotion_id');
            $table->foreign('promotion_id')->references('id')->on('promotions');

            $table->string('discount_in',30)->nullable();
            $table->string('discount_value',30)->nullable();

            $table->float('net_price',15,2);
            $table->float('sale_price',15,2);

            $table->enum('vat_type',['including','excluding']);
            $table->float('vat',8,2);

            $table->float('discount_price',15,2);


            $table->enum('status',['0','1'])->default(1);
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
        Schema::dropIfExists('product_promotion');
    }
}
