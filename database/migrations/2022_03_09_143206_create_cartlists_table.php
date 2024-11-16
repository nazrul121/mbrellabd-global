<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartlistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cartlists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries');

            $table->string('session_id');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedBigInteger('promotion_id')->nullable();
            $table->foreign('promotion_id')->references('id')->on('promotions');

            $table->unsignedBigInteger('variation_option_id')->nullable();
            $table->foreign('variation_option_id')->references('id')->on('variation_options');

            $table->unsignedBigInteger('product_combination_id')->nullable();
            $table->foreign('product_combination_id')->references('id')->on('product_combinations')->onDelete('set null');;

            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->foreign('campaign_id')->references('id')->on('campaigns');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('qty')->default(1);
            $table->enum('status',['1','0'])->default(1);
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
        Schema::dropIfExists('cartlists');
    }
}
