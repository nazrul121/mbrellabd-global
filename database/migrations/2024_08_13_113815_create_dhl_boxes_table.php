<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDHLBoxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dhl_boxes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->foreign('group_id')->references('id')->on('groups');

            $table->unsignedBigInteger('inner_group_id');
            $table->foreign('inner_group_id')->references('id')->on('inner_groups');

            $table->unsignedBigInteger('child_group_id')->nullable();
            $table->foreign('child_group_id')->references('id')->on('child_groups');

            $table->integer('small_qty');
            $table->integer('small_weight');
            $table->integer('large_qty');
            $table->integer('large_weight');
            $table->integer('flyer_small_qty');
            $table->integer('flyer_small_weight');
            $table->integer('flyer_large_qty');
            $table->integer('flyer_large_weight');
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
        Schema::dropIfExists('dhl_boxes');
    }
}
