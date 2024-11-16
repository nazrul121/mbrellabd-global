<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChildGroupSeasonProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('child_group_season_product', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('group_id')->nullable();
            $table->foreign('group_id')->references('id')->on('groups');

            $table->unsignedBigInteger('inner_group_id')->nullable();
            $table->foreign('inner_group_id')->references('id')->on('inner_groups');

            $table->unsignedBigInteger('child_group_season_id')->nullable();
            $table->foreign('child_group_season_id')->references('id')->on('child_group_season');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

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
        Schema::dropIfExists('child_group_season_product');
    }
}
