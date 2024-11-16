<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemEditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_item_editions', function (Blueprint $table) {
            $table->id();

            $table->string('type');

            $table->unsignedBigInteger('order_item_id');
            $table->foreign('order_item_id')->references('id')->on('order_items');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedBigInteger('product_combination_id')->nullable();
            $table->foreign('product_combination_id')->references('id')->on('product_combinations');

            $table->integer('qty')->nullable();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

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
        Schema::dropIfExists('order_item_editions');
    }
}
