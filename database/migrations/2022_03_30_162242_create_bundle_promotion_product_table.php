<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBundlePromotionProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bundle_promotion_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bundle_promotion_id')->nullable();
            $table->foreign('bundle_promotion_id')->references('id')->on('bundle_promotions');

            $table->unsignedBigInteger('group_id');
            $table->foreign('group_id')->references('id')->on('groups');

            $table->unsignedBigInteger('inner_group_id')->nullable();
            $table->foreign('inner_group_id')->references('id')->on('inner_groups');

            $table->unsignedBigInteger('child_group_id')->nullable();
            $table->foreign('child_group_id')->references('id')->on('child_groups');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedBigInteger('product_combination_id')->nullable();
            $table->foreign('product_combination_id')->references('id')->on('product_combinations');

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
        Schema::dropIfExists('bundle_promotion_product');
    }
}
